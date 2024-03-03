"""
    Robot Learning
    Exercise 2

    Reinforcement Learning 

    Polito A-Y 2023-2024
"""
import torch
import gym
import numpy as np
import argparse
import matplotlib.pyplot as plt
import seaborn as sns
import pandas as pd
import sys
from agent import Agent, Policy
from utils import get_space_dim

import sys

change=0

# Parse script arguments
def parse_args(args=sys.argv[1:]):
    parser = argparse.ArgumentParser()
    parser.add_argument("--test", "-t", type=str, default=None,
                        help="Model to be tested")
    parser.add_argument("--env", type=str, default="CartPole-v0",
                        help="Environment to use")
    parser.add_argument("--train_episodes", type=int, default=500,
                        help="Number of episodes to train for")
    parser.add_argument("--render_training", action='store_true',
                        help="Render each frame during training. Will be slower.")
    parser.add_argument("--render_test", action='store_true', help="Render test")
    parser.add_argument("--central_point", type=float, default=0.0,
                        help="Point x0 to fluctuate around")
    parser.add_argument("--random_policy", action='store_true', help="Applying a random policy training")
    return parser.parse_args(args)


# Policy training function
def train(agent, env, train_episodes, early_stop=True, render=False,
          silent=False, train_run_id=0, x0=0, random_policy=False):
    # Arrays to keep track of rewards
    reward_history, timestep_history = [], []
    average_reward_history = []

    # Run actual training
    for episode_number in range(train_episodes):
        reward_sum, timesteps = 0, 0
        done = False
        # Reset the environment and observe the initial state (it's a random initial state with small values)
        observation = env.reset()
        change=0
        barrier=0
        ok=0
        # Loop until the episode is over
        while not done:
            # Get action from the agent
            action, action_probabilities = agent.get_action(observation)

            if random_policy:
                # Task 1.1
                """
                Sample a random action from the action space
                """
                action= np.random.randint(0,2)

            previous_observation = observation

            # Perform the action on the environment, get new state and reward
            # note that after env._max_episode_steps the episode is over, if we stay alive that long
            observation, reward, done, info = env.step(action)

            # Task 3.1
            """
                Use a different reward, overwriting the original one
            """
            # for the third reward
            if np.abs(observation[0])>=1.0:     #passed by the barrier, start decrease velocity
                barrier=1
            elif np.abs(observation[0])>=1.5:   #reached the edge, start coming back
                change=1
            elif 0 <= np.abs(observation[0]) <= 0.1:    #reset flags
                barrier=0
                change=0
            
            if episode_number > 200:
                ok=1

            reward = new_reward(observation, x0, previous_observation, change, barrier, ok)

            # Store action's outcome (so that the agent can improve its policy)
            agent.store_outcome(previous_observation, action_probabilities, action, reward)

            # Draw the frame, if desired
            if render:
                env.render()

            # Store total episode reward
            reward_sum += reward
            timesteps += 1

        if not silent:
            print("Episode {} finished. Total reward: {:.3g} ({} timesteps)"
                  .format(episode_number, reward_sum, timesteps))

        # Bookkeeping (mainly for generating plots)
        reward_history.append(reward_sum)
        timestep_history.append(timesteps)
        if episode_number > 100:
            avg = np.mean(reward_history[-100:])
        else:
            avg = np.mean(reward_history)
        average_reward_history.append(avg)

        # If we managed to stay alive for 15 full episodes, assume it's learned
        # (in the default setting)
        if early_stop and np.mean(timestep_history[-15:]) == env._max_episode_steps:
            if not silent:
                print("Looks like it's learned. Finishing up early")
            break

        # Let the agent do its magic (update the policy)
        agent.episode_finished(episode_number)

    # Store the data in a Pandas dataframe for easy visualization
    data = pd.DataFrame({"episode": np.arange(len(reward_history)),
                         "train_run_id": [train_run_id]*len(reward_history),
                         "reward": reward_history,
                         "mean_reward": average_reward_history})
    return data


# Function to test a trained policy
def test(agent, env, episodes, render=False, x0=0):
    test_reward, test_len = 0, 0

    episodes = 100
    print('Num testing episodes:', episodes)

    env._max_episode_steps = 500    # Test on 500 timesteps
    # for the third reward function
    env._max_episode_steps = 1500    # Test on 1500 timesteps 

    for ep in range(episodes):
        print('episode: ',ep)
        done = False
        observation = env.reset()
        change=0
        barrier=0

        while not done:
        # Task 1.2
            """
            Test on 500 timesteps
            """

            action, _ = agent.get_action(observation, evaluation=True)  # Similar to the training loop above -
                                                                        # get the action, act on the environment, save total reward
                                                                        # (evaluation=True makes the agent always return what it thinks to be
                                                                        # the best action - there is no exploration at this point)
            previous_observation= observation
            observation, reward, done, info = env.step(action)

            # Task 3.1
            """
                Use a different reward, overwriting the original one
            """
            # for the third reward function
            if np.abs(observation[0])>=1.0:     #passed by the barrier, start decrease velocity
                barrier=1
            elif np.abs(observation[0])>=1.5:   #reached the edge, start coming back
                change=1
            elif 0 <= np.abs(observation[0]) <= 0.1:    #reset flags
                barrier=0
                change=0
            

            reward = new_reward(observation, x0, previous_observation, change, barrier)

            if render:
                env.render()
            test_reward += reward
            test_len += 1
    print("Average test reward:", test_reward/episodes, "episode length:", test_len/episodes)


def new_reward(state, x0, prev_state, change=0, barrier=0, ok=1):
    # Task 3.1
    """
        Use a different reward, overwriting the original one
    """
    # 1 reward
    """ reward = 1-(np.linalg.norm(state,2))**2 """

    # 2 reward
    """ ref_state= np.array([ x0,0,0,0])
    reward= 5-(np.linalg.norm((state-ref_state),0)) """

    # 3 first attempt
    """reward= 1e-2    # not zero since it is still alive

    reward_velocity=1
    if 0.1 <= np.abs(state[1]) <= 5:
        reward_velocity *= 1e-3
    elif 5.1 <= np.abs(state[1]) <= 10:
        reward_velocity *= 0.9
    elif 10.1 <= np.abs(state[1]):
        reward_velocity *= 0.5
    
    reward_pole=1
    if np.abs(state[2]) < 1e-1 or np.abs(state[3]) < 1e-1:
        reward_pole *= 0.9
    else:
        reward_pole *= 1e-2
    
    reward_position=1
    if 0 <= np.abs(state[0]) <= 1.5:
        reward_position *= 1e-3
    elif 1.6 <= np.abs(state[0]) <= 2.4:
        reward_position *= 0.9
    else:
        reward_position *= 0
    
    reward += 0.8 * reward_velocity + 0.0 * reward_pole + 0.8 * reward_position
    """
    #3 second attempt

    """ if np.abs(state[0]) > 2.4:
        penalty=-10
    else:
        penalty=0
    pole_reward= - ( np.abs(state[2]) - np.abs(prev_state[2]))
    position_reward= np.abs(state[0]) - np.abs(prev_state[0])
    velocity_reward= np.abs(state[1]) - np.abs(prev_state[1])

    reward= 100*pole_reward + 60*position_reward + 80*velocity_reward + penalty """
    
    #3 third attempt (the correct one)
    reward=1e-1
    print("velocity: ", state[1])
    if not barrier and not change:      # in between positive and negative barriers
        if ok:
            reward += np.abs(state[1])
        else:
            reward +=0
        
    elif barrier and not change:        
        if state[0]>0:
            reward += prev_state[1]-state[1]
        else:
            reward += -(prev_state[1]-state[1])
    elif barrier and change:
        if state[0]>0:
            reward += -state[1]
        else:
            reward += state[1]

    return reward

# The main function
def main(args):
    # Create a Gym environment with the argument CartPole-v0 (already embedded in)
    env = gym.make(args.env)

    # Task 1.2
    """
    # For CartPole-v0 - change the maximum episode length
    """
   # env._max_episode_steps = 200
    env._max_episode_steps = 500
   # for the third reward function
    env._max_episode_steps = 1500

    # Get dimensionalities of actions and observations
    action_space_dim = get_space_dim(env.action_space)
    observation_space_dim = get_space_dim(env.observation_space)

    # Instantiate agent and its policy
    policy = Policy(observation_space_dim, action_space_dim)
    agent = Agent(policy)

    # Print some stuff
    print("Environment:", args.env)
    print("Training device:", agent.train_device)
    print("Observation space dimensions:", observation_space_dim)
    print("Action space dimensions:", action_space_dim)

    # If no model was passed, train a policy from scratch.
    # Otherwise load the policy from the file and go directly to testing.
    if args.test is None:
        # Train
        training_history = train(agent, env, args.train_episodes, False, args.render_training, x0=args.central_point, random_policy=args.random_policy)

        # Save the model
        model_file = "%s_policy5_params.ai" % args.env
        torch.save(policy.state_dict(), model_file)
        print("Model saved to", model_file)

        # Plot rewards
        sns.lineplot(x="episode", y="reward", data=training_history, color='blue', label='Reward')
        sns.lineplot(x="episode", y="mean_reward", data=training_history, color='orange', label='100-episode average')
        plt.legend()
        plt.title("Reward history (%s)" % args.env)
        plt.show()
        print("Training finished.")
    else:
        # Test
        print("Loading model from", args.test, "...")
        state_dict = torch.load(args.test)
        policy.load_state_dict(state_dict)
        print("Testing...")
        test(agent, env, args.train_episodes, args.render_test)


# Entry point of the script
if __name__ == "__main__":
    args = parse_args()
    main(args)

