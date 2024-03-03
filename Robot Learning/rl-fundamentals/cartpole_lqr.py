"""
    Robot Learning
    Exercise 2

    Linear Quadratic Regulator

    Polito A-Y 2023-2024
"""
import gym
import numpy as np
from scipy import linalg     # get riccati solver
import argparse
import matplotlib.pyplot as plt
import sys
from utils import get_space_dim, set_seed
import pdb 
import time

# Parse script arguments
def parse_args(args=sys.argv[1:]):
    parser = argparse.ArgumentParser()
    parser.add_argument("--env", type=str, default="CartPole-v0",
                        help="Environment to use")
    parser.add_argument('--seed', default=0, type=int, help='Random seed')
    parser.add_argument("--time_sleep", action='store_true',
                        help="Add timer for visualizing rendering with a slower frame rate")
    parser.add_argument("--mode", type=str, default="control",
                        help="Type of test ['control', 'multiple_R']")
    return parser.parse_args(args)

def linerized_cartpole_system(mp, mk, lp, g=9.81):
    mt=mp+mk
    a = g/(lp*(4.0/3 - mp/(mp+mk)))
    # state matrix
    A = np.array([[0, 1, 0, 0],
                [0, 0, a, 0],
                [0, 0, 0, 1],
                [0, 0, a, 0]])

    # input matrix
    b = -1/(lp*(4.0/3 - mp/(mp+mk)))
    B = np.array([[0], [1/mt], [0], [b]])
    return A, B

def optimal_controller(A, B, R_value=1):
    R = R_value*np.eye(1, dtype=int)  # choose R (weight for input)
    Q = 5*np.eye(4, dtype=int)        # choose Q (weight for state)
   # solve riccati equation
    P = linalg.solve_continuous_are(A, B, Q, R)

    # calculate optimal controller gain
    K = np.dot(np.linalg.inv(R),
            np.dot(B.T, P))
    return K

def apply_state_controller(K, x):
    # feedback controller
    u = -np.dot(K, x)   # u = -Kx
    if u > 0:
        return 1, u     # if force_dem > 0 -> move cart right
    else:
        return 0, u     # if force_dem <= 0 -> move cart left

def multiple_R(env, mp, mk, l, g, time_sleep=False, terminate=False):
    """
    Vary the value of R within the range [0.01, 0.1, 10, 100] and plot the forces 
    """
    steps=400
    valuesR= [0.01, 0.1, 10, 100]
    forces=np.zeros((4,steps))
    print("inside R")

    for j in range(len(valuesR)):
        print("R: ",valuesR[j])
        invert=0
        terminate= False

        obs = env.reset()    # Reset the environment for a new episode
    
        A, B = linerized_cartpole_system(mp, mk, l, g)
        K = optimal_controller(A, B, R_value=valuesR[j])    # Re-compute the optimal controller for the current R value

        steps=400
        states=[]

        for i in range(1000):

            env.render()
            if time_sleep:
                time.sleep(.1)
            
            # get force direction (action) and force value (force)
            action, force = apply_state_controller(K, obs)

            if i==0:
                if force[0]>0:
                    invert=1
                else:
                    invert=0
            if invert:
                forces[j,i]=-force[0]
            else:
                forces[j,i]=force[0]

            # absolute value, since 'action' determines the sign, F_min = -10N, F_max = 10N
            abs_force = abs(float(np.clip(force[0], -10, 10)))
            
            # change magnitude of the applied force in CartPole
            env.env.force_mag = abs_force

            # apply action
            obs, reward, done, _ = env.step(action)
            states.append(obs)
            
            if i>=steps-1:
                terminate=True

            if terminate and done:
                print(f'Terminated after {i+1} iterations.')
                break

    # plotting forces

    interval= np.arange(0,steps,1)
    fig,ax=plt.subplots()
    ax.plot(interval,forces[0,:],'r',interval,forces[1,:],'g',interval,forces[2,:],'b',interval,forces[3,:],'m')
    ax.grid()
    ax.legend(['0.01', '0.1', '10', '100'])
    plt.show()
   
    return

def control(env, mp, mk, l, g, time_sleep=False, terminate=False):
    """
    Control using LQR
    """

    obs = env.reset()    # Reset the environment for a new episode
    
    A, B = linerized_cartpole_system(mp, mk, l, g)
    K = optimal_controller(A, B)    # Re-compute the optimal controller for the current R value

    steps=400
    states=[]

    for i in range(1000):

        env.render()
        if time_sleep:
            time.sleep(.1)
        
        # get force direction (action) and force value (force)
        action, force = apply_state_controller(K, obs)

        # absolute value, since 'action' determines the sign, F_min = -10N, F_max = 10N
        abs_force = abs(float(np.clip(force[0], -10, 10)))
        
        # change magnitude of the applied force in CartPole
        env.env.force_mag = abs_force

        # apply action
        obs, reward, done, _ = env.step(action)
        states.append(obs)
        
        if i>=steps-1:
            terminate=True

        if terminate and done:
            x= np.vstack(states)
            interval= np.arange(0,steps,1)

            #plotting state values
            fig,axis= plt.subplots(4,1)
            lgnds=['x','xdot','theta','thetadot']
            for j in range(4):
                axis[j].plot(interval,x[:,j],label=lgnds[j])
                axis[j].xlabel='iterations'
                axis[j].ylabel=lgnds[j]
                axis[j].grid()
                axis[j].legend()
            plt.show()

            print(f'Terminated after {i+1} iterations.')
            break

# The main function
def main(args):
    # Create a Gym environment with the argument CartPole-v0 (already embedded in)
    env = gym.make(args.env)
    
    # Get dimensionalities of actions and observations
    action_space_dim = get_space_dim(env.action_space)
    observation_space_dim = get_space_dim(env.observation_space)

    # Print some stuff
    print("Environment:", args.env)
    print("Observation space dimensions:", observation_space_dim)
    print("Action space dimensions:", action_space_dim)

    set_seed(args.seed)    # seed for reproducibility
    env.env.seed(args.seed)
    
    mp, mk, l, g = env.masspole, env.masscart, env.length, env.gravity

    if args.mode == "control":
        control(env, mp, mk, l, g, args.time_sleep, terminate=False)
    elif args.mode == "multiple_R":
        multiple_R(env, mp, mk, l, g, args.time_sleep, terminate=False)

    env.close()

# Entry point of the script
if __name__ == "__main__":
    args = parse_args()
    main(args)

