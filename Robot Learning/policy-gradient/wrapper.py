import torch
import gym
import numpy as np
import argparse
import matplotlib.pyplot as plt
from agent import Agent, Policy
from cp_cont import CartPoleEnv
import pandas as pdfrom 
from cartpole import train,test

import sys

import os
os.environ["KMP_DUPLICATE_LIB_OK"]="TRUE"

test_returns=[]
nruns=10
id=9        # id=4 -> T1a, id=5 -> T1b, id=6 -> T1c, id=7 -> T1b_30, id=8 -> T1b_40, id=9 -> T1b_100
for i in range(nruns):
    print("\nrun id: {}".format(i+1))
    train("ContinuousCartPole-v0", print_things=False, train_run_id=id, train_episodes=1000)
    model= "model_ContinuousCartPole-v0_" + str(id) + ".mdl"
    state_dict = torch.load(model)
    avg_test= test("ContinuousCartPole-v0", 100, state_dict, False)
    test_returns.append(avg_test)

avg= np.mean(test_returns)
plot_avg= list(avg * np.ones((nruns,)))
plt.plot(test_returns)
plt.plot(plot_avg)
plt.legend(["Average Test Rewards", "Overall Average"])
plt.xlabel('Training-Test session id', labelpad=12, fontweight='bold')
plt.ylabel('cumulative reward', labelpad=12, fontweight='bold')
plt.title("Average Test Rewards history")
plt.show()