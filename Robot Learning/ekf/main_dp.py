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

from ExtendedKalmanFilter_dp import ExtendedKalmanFilter_dp

# Discretization time step (frequency of measurements)
deltaTime=0.01

# Initial true state
x0 = np.array([np.pi/4, 0.5, np.pi/3, 1])

# Simulation duration in timesteps
simulationSteps=1000
totalSimulationTimeVector=np.arange(0, simulationSteps*deltaTime, deltaTime)

# System dynamics (continuous, non-linear) in state-space representation (https://en.wikipedia.org/wiki/State-space_representation)
def stateSpaceModel(x,t):
    """
        Dynamics may be described as a system of first-order
        differential equations: 
        dx(t)/dt = f(t, x(t))
    """
    g=9.81
    l1 = 1  # Length of the pendulum 
    l2 = 2
    m1 = 1
    m2 = 1
    th1=x[0]
    th1d=x[1]
    th2=x[2]
    th2d=x[3]

    th1dd=(-l2*m2*np.sin(th1-th2)*th2d**2-l1*m1*th1d-g*np.sin(th1)*(m1+m2)+m2*np.cos(th1-th2)*np.sin(th2)+m2*np.cos(th1-th2)*l1*th1d**2*np.sin(th1-th2))/(l1*(m1+m2)+l1*m2*np.cos(th1-th2)**2)
    th2dd=(l1*np.cos(th1 - th2)*(- l1*m2*np.cos(th1 - th2)*np.sin(th1 - th2)*th1d**2 + l1*m1*th1d + l2*m2*np.sin(th1 - th2)*th2d**2 - m2*np.cos(th1 - th2)*np.sin(th2) + g*np.sin(th1)*(m1 + m2)))/(l2*(l1*m2*np.cos(th1 - th2)**2 + l1*(m1 + m2))) - (l1*th1d**2*np.sin(th1 - th2))/l2 - (g*np.sin(th2))/l2
        
    dxdt=np.array([th1d,th1dd,th2d,th2dd])
    return dxdt

# True solution x(t)
x_t_true = odeint(stateSpaceModel, x0, totalSimulationTimeVector)



"""
    EKF initialization
"""
# Initial state belief distribution (EKF assumes Gaussian distributions)
x_0_mean = np.zeros(shape=(4,1))  # column-vector
x_0_mean[0] = x0[0] + 3*np.random.randn()
x_0_mean[1] = x0[1] + 3*np.random.randn()
x_0_mean[2] = x0[2] + 3*np.random.randn()
x_0_mean[3] = x0[3] + 3*np.random.randn()
x_0_cov = 10*np.eye(4,4)  # initial value of the covariance matrix

# Process noise covariance matrix (close to zero, we do not want to model noisy dynamics)
Q=0.00001*np.eye(4,4)

# Measurement noise covariance matrix for EKF
R = np.array([[0.05,0],[0,0.05]])


# create the extended Kalman filter object
EKF = ExtendedKalmanFilter_dp(x_0_mean, x_0_cov, Q, R, deltaTime)



"""
    Simulate process
"""
measurement_noise_var = 0.05  # Actual measurement noise variance (uknown to the user)

for t in range(simulationSteps-1):
    # PREDICT step
    EKF.forwardDynamics()
    
    # Measurement model
    z_t=np.zeros((2,1))
    z_t[0,0] = x_t_true[t, 0] + np.sqrt(measurement_noise_var)*np.random.randn()
    z_t[1,0] = x_t_true[t, 2] + np.sqrt(measurement_noise_var)*np.random.randn()
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
ax1.plot(totalSimulationTimeVector, x_t_true[:,0],label='real theta1')
ax1.plot(totalSimulationTimeVector, xest[:,0], label='estimated theta1')

fig2, ax2= plt.subplots()
ax2.plot(totalSimulationTimeVector, x_t_true[:,1], label='real omega1')
ax2.plot(totalSimulationTimeVector, xest[:,1], label='estimated omega1')

ax1.legend()
ax2.legend()

ax1.set_xlabel('time steps')
ax2.set_xlabel('time steps')

ax1.set_ylabel('theta1')
ax2.set_ylabel('omega1')

ax1.grid()
ax2.grid()

fig3, ax3= plt.subplots()
ax3.plot(totalSimulationTimeVector, x_t_true[:,2],label='real theta2')
ax3.plot(totalSimulationTimeVector, xest[:,2], label='estimated theta2')

fig4, ax4= plt.subplots()
ax4.plot(totalSimulationTimeVector, x_t_true[:,3], label='real omega2')
ax4.plot(totalSimulationTimeVector, xest[:,3], label='estimated omega2')

ax3.legend()
ax4.legend()

ax3.set_xlabel('time steps')
ax4.set_xlabel('time steps')

ax3.set_ylabel('theta2')
ax4.set_ylabel('omega2')

ax3.grid()
ax4.grid()

l1 = 1  # Length of the pendulum 
l2 = 2

fig5, ax5= plt.subplots()
ax5.plot(l1*np.sin(x_t_true[:,0])+l2*np.sin(x_t_true[:,2]), -l1*np.cos(x_t_true[:,0])-l2*np.cos(x_t_true[:,2]), color="black",linewidth=2, linestyle="dashed", label='real trajectory')
ax5.plot(l1*np.sin(xest[:,0])+l2*np.sin(xest[:,2]), -l1*np.cos(xest[:,0])-l2*np.cos(xest[:,2]), color="green",linewidth=1, label='EKF trajectory')
ax5.legend()

plt.show()

