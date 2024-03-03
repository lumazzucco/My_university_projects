
#!/usr/bin/env python

import rospy
from rospy_tutorials.msg import Floats
import numpy as np



def stateSpaceModel(x, t):

	g=9.31
	l=1       
	dxdt = np.array([ [x[1,0] ], [-(g/l)*np.sin(x[0,0])]])
	return dxdt


def discreteTimeDynamics( x_t):
	dT=0.01
	x_t= np.array([[ x_t[0] ],[ x_t[1] ]])	#convert from list to np.array
	x_tp1 = x_t + dT*stateSpaceModel(x_t, None)
	return x_tp1


def pendulum():

	pub= rospy.Publisher('TrueState', Floats, queue_size=100)
	rospy.init_node('pendulum', anonymous= True)
	rate = rospy.Rate(100)
	x0= [np.pi/3, 0.5]
	xcurr=x0
	time=0
	dT=0.01
	
	while not rospy.is_shutdown():
		time+=dT
		xcurr = discreteTimeDynamics(xcurr)
		xcurr=[xcurr[0,0], xcurr[1,0]]	#convert from np.array to list 
		msg= Floats( data=xcurr)
		rospy.loginfo(msg)
		pub.publish(msg)
		rate.sleep()

if __name__ == '__main__':
	try:
		pendulum()
	except rospy.ROSInterruptException:
		pass
		
		



    

