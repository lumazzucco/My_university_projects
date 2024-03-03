import gym
import numpy as np
from matplotlib import pyplot as plt
from time import sleep
import random
import seaborn as sns
import pandas as pd

import sys

np.random.seed(123)

env = gym.make('CartPole-v0')
env.seed(321)

# Whether to perform training or use the stored .npy file
MODE = 'TRAINING' # TRAINING, TEST

episodes = 20000
test_episodes = 100
num_of_actions = 2  # 2 discrete actions for Cartpole

# Reasonable values for Cartpole discretization
discr = 16
x_min, x_max = -2.4, 2.4
v_min, v_max = -3, 3
th_min, th_max = -0.3, 0.3
av_min, av_max = -4, 4

# Parameters
gamma = 0.98
alpha = 0.1
constant_eps = 0.2
b = 2222

# Create discretization grid
x_grid = np.linspace(x_min, x_max, discr)
v_grid = np.linspace(v_min, v_max, discr)
th_grid = np.linspace(th_min, th_max, discr)
av_grid = np.linspace(av_min, av_max, discr)

# Initialize Q values
q_grid = np.zeros((discr, discr, discr, discr, num_of_actions))
#q_grid = 50*np.ones((discr, discr, discr, discr, num_of_actions))       # task3.3

if MODE == 'TEST':
    q_grid = np.load('q_values.npy')

def find_nearest(array, value):
    return np.argmin(np.abs(array - value))

def get_cell_index(state):
    """Returns discrete state from continuous state"""
    x = find_nearest(x_grid, state[0])
    v = find_nearest(v_grid, state[1])
    th = find_nearest(th_grid, state[2])
    av = find_nearest(av_grid, state[3])
    return x, v, th, av


def get_action(state, q_values, greedy=False):
    x, v, th, av = get_cell_index(state)

    if greedy: # TEST -> greedy policy
        if q_values[x,v,th,av,0] > q_values[x,v,th, av,1]:      # greedy w.r.t. q_grid
            best_action_estimated = 0
        else: 
            best_action_estimated = 1 

        return best_action_estimated

    else: # TRAINING -> epsilon-greedy policy
        if np.random.rand() < epsilon:
            # Random action
            action_chosen = np.random.choice([1,0],1,p=[0.5,0.5])[0]  # choose random action with equal probability among all actions

            return action_chosen
        else:
            # Greedy action
            if q_values[x,v,th,av,0] > q_values[x,v,th, av,1]:      # greedy w.r.t. q_grid
                best_action_estimated = 0
            else: 
                best_action_estimated = 1
            return best_action_estimated


def update_q_value(old_state, action, new_state, reward, done, q_array):
    old_cell_index = get_cell_index(old_state)
    new_cell_index = get_cell_index(new_state)

    # Target value used for updating our current Q-function estimate at Q(old_state, action)
    if done is True:
        target_value = reward  # HINT: if the episode is finished, there is not next_state. Hence, the target value is simply the current reward.
    else:
        if q_array[new_cell_index[0], new_cell_index[1], new_cell_index[2], new_cell_index[3], 0] > q_array[new_cell_index[0], new_cell_index[1], new_cell_index[2], new_cell_index[3],1] :
            max= q_array[new_cell_index[0], new_cell_index[1], new_cell_index[2], new_cell_index[3], 0]
        else:
            max= q_array[new_cell_index[0], new_cell_index[1], new_cell_index[2], new_cell_index[3], 1]    
        
        target_value = reward + gamma * max

    # Update Q value
    Qold = q_array[old_cell_index[0], old_cell_index[1], old_cell_index[2], old_cell_index[3], action]
    q_grid[old_cell_index[0], old_cell_index[1], old_cell_index[2], old_cell_index[3], action] = Qold + alpha*(target_value-Qold)

    return


# Training loop
ep_lengths, epl_avg = [], []
returns = []

for ep in range(episodes+test_episodes):
    test = ep > episodes

    if MODE == 'TEST':
        test = True

    state, done, steps = env.reset(), False, 0

    #epsilon = constant_eps  # change to GLIE schedule (task 3.1) or 0 (task 3.3)
    epsilon = b/(b+ep)  # GLIE
   # epsilon = 0
    ret = 0
    while not done:
        action = get_action(state, q_grid, greedy=test)
        new_state, reward, done, _ = env.step(action)
        if not test:
            update_q_value(state, action, new_state, reward, done, q_grid)
        else:
            env.render()

        state = new_state
        steps += 1
        ret += reward
    if not test:
        returns.append(ret)
    ep_lengths.append(steps)
    epl_avg.append(np.mean(ep_lengths[max(0, ep-500):]))
    if ep % 200 == 0:
        print("Episode {}, average timesteps: {:.2f}".format(ep, np.mean(ep_lengths[max(0, ep-200):])))
        print('Epsilon:', epsilon)


if MODE == 'TEST':
    sys.exit()

# Save the Q-value array
np.save("q_values.npy", q_grid)

# plot episodes returns

fig, axs= plt.subplots()
axs.plot(np.arange(0,episodes+1),returns)
plt.title("Q-learning with initial null Q ")
plt.xlabel("episodes")
plt.ylabel("returns")
#plt.savefig("pictures/Qnull.png")
plt.show()