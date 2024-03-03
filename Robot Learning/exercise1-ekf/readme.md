# Exercises 1 -  Extended Kalman Filter

'main.py' and 'ExtendedKalmanFilter.py' contain code for the EKF applied to the basic pandulum, 
'main_dp.py' and 'ExtendedKalmanFilter_dp.py' contain code for the EKF applied to the double pandulum,
the folder 'pictures' contain the collected images used to produce the report,
'rqt_multiplot2' represents the configuration file used for rqt_multiplot in ROS,
'exercise1' corresponds to the package folder containing the python scripts related to pendulum, sensor and ekf nodes. It is structured as follows:

exercise1
   - include
   - scripts
       - ekf.py
       - pendulum.py
       - sensor.py
   - src
       - __init__.py
       - ExtendedKalmanFilter.py (module to import the class, used in ekf.py)
   - CMakeLists.txt
   - package.xml
   - setup.py

