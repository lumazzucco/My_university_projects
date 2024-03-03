"""
    Robot Learning
    Exercise 1

    Extended Kalman Filter

    Polito A-Y 2023-2024
"""
import pdb

import numpy as np
import matplotlib.pyplot as plt
from scipy.integrate import odeint

from ExtendedKalmanFilter import ExtendedKalmanFilter

# Discretization time step (frequency of measurements)
deltaTime=0.01

# Initial true state
x0 = np.array([np.pi/3, 0.5])

# Simulation duration in timesteps
simulationSteps=400
totalSimulationTimeVector=np.arange(0, simulationSteps*deltaTime, deltaTime)

# System dynamics (continuous, non-linear) in state-space representation (https://en.wikipedia.org/wiki/State-space_representation)
def stateSpaceModel(x,t):
    """
        Dynamics may be described as a system of first-order
        differential equations: 
        dx(t)/dt = f(t, x(t))
    """
    g=9.81
    l=1
    dxdt=np.array([x[1], -(g/l)*np.sin(x[0])])
    return dxdt

# True solution x(t)
x_t_true = odeint(stateSpaceModel, x0, totalSimulationTimeVector)



"""
    EKF initialization
"""
# Initial state belief distribution (EKF assumes Gaussian distributions)
x_0_mean = np.zeros(shape=(2,1))  # column-vector
x_0_mean[0] = x0[0] + 3*np.random.randn()
x_0_mean[1] = x0[1] + 3*np.random.randn()
x_0_cov = 10*np.eye(2,2)  # initial value of the covariance matrix

# Process noise covariance matrix (close to zero, we do not want to model noisy dynamics)
Q=0.00001*np.eye(2,2)
#Q=0.1*np.eye(2,2)

# Measurement noise covariance matrix for EKF
R = np.array([[0.05]])
#R = np.array([[0.5]])
#R = np.array([[5]])


# create the extended Kalman filter object
EKF = ExtendedKalmanFilter(x_0_mean, x_0_cov, Q, R, deltaTime)



"""
    Simulate process
"""
measurement_noise_var = 0.05  # Actual measurement noise variance (uknown to the user)

for t in range(simulationSteps-1):
    # PREDICT step
    EKF.forwardDynamics()
    
    # Measurement model
    z_t = x_t_true[t, 0] + np.sqrt(measurement_noise_var)*np.random.randn()

    # UPDATE step
    EKF.updateEstimate(z_t)



"""
    Plot the true vs. estimated state variables
"""
### Estimates
# EKF.posteriorMeans
# EKF.posteriorCovariances

xest= (np.hstack(EKF.posteriorMeans)).T

# performance evaluation

sum_squared_errors=0
for i in range(simulationSteps):
    sum_squared_errors+=np.linalg.norm((xest[i,:]-x_t_true[i,:]),2)**2
RMSE=np.sqrt(1/simulationSteps*sum_squared_errors)

print("RMSE: ", RMSE)

fig1, ax1= plt.subplots()
ax1.plot(totalSimulationTimeVector, x_t_true[:,0],label='real theta')
ax1.plot(totalSimulationTimeVector, xest[:,0], label='estimated theta')

fig2, ax2= plt.subplots()
ax2.plot(totalSimulationTimeVector, x_t_true[:,1], label='real omega')
ax2.plot(totalSimulationTimeVector, xest[:,1], label='estimated omega')

ax1.legend()
ax2.legend()

ax1.set_xlabel('time steps')
ax2.set_xlabel('time steps')

ax1.set_ylabel('theta')
ax2.set_ylabel('omega')

ax1.grid()
ax2.grid()

plt.show()

