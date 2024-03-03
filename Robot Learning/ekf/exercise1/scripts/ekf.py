#!/usr/bin/env python

import rospy
from std_msgs.msg import Float32
from rospy_tutorials.msg import Floats
import numpy as np
from exercise1.ExtendedKalmanFilter import ExtendedKalmanFilter

"""
EKF initialization
"""

deltaTime= 0.01

# Initial true state
x0 = np.array([np.pi/3, 0.5])

# Initial state belief distribution (EKF assumes Gaussian distributions)
x_0_mean = np.zeros(shape=(2,1))  # column-vector
x_0_mean[0] = x0[0] + 3*np.random.randn()
x_0_mean[1] = x0[1] + 3*np.random.randn()
x_0_cov = 10*np.eye(2,2)  # initial value of the covariance matrix

# Process noise covariance matrix (close to zero, we do not want to model noisy dynamics)
Q=0.00001*np.eye(2,2)

# Measurement noise covariance matrix for EKF
R = np.array([[0.05]])


# create the extended Kalman filter object
EKF = ExtendedKalmanFilter(x_0_mean, x_0_cov, Q, R, deltaTime)

pub= rospy.Publisher('EstimatedState', Floats, queue_size=100)

def ekf_algorithm(z_t):

	"""
	    Simulate process
	"""

	# PREDICT step
	EKF.forwardDynamics()

	# UPDATE step
	EKF.updateEstimate(z_t)	# z_t from sensor node
    
    
def callback(data):
	
	zt=data.data
	ekf_algorithm(zt)
	estimate= EKF.posteriorMeans[-1]
	msg="\nestimated theta: " + str(estimate[0,0]) + "\nestimated omega: " +  str(estimate[1,0]) 
	ret= Floats(data=[estimate[0,0],estimate[1,0]])
	rospy.loginfo(msg)
	pub.publish(ret)

def ekf():
	
	rospy.init_node('Ekf', anonymous= True)
	rospy.Subscriber('SensedState', Float32, callback)
	rospy.spin()

if __name__=='__main__':
	ekf()
	
	
	
	
	
	
	
	
	
	

	
