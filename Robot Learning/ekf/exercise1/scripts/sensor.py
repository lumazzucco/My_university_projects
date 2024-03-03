
#!/usr/bin/env python

import rospy
import numpy as np
from rospy_tutorials.msg import Floats
from std_msgs.msg import Float32

pub=rospy.Publisher('SensedState', Float32, queue_size=100)

def callback(data):
	noise= data.data[0] + np.sqrt(0.05)*np.random.randn()
	rospy.loginfo("sensed state: " + str(noise))
	pub.publish(noise)

def sensor():

	rospy.init_node('Sensor', anonymous= True)
	#pub= rospy.Publisher('SensedState', Float32, queue_size=10)
	rospy.Subscriber('TrueState', Floats, callback)
	rate = rospy.Rate(100)
	rospy.spin()

if __name__=='__main__':
	sensor()
	
	
