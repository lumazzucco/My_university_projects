"""
    Implementation of the Extended Kalman Filter
    for an unactuated double pendulum system
"""
import numpy as np 

class ExtendedKalmanFilter_dp(object):
    
    
    def __init__(self, x0, P0, Q, R, dT):
        """
           Initialize EKF
            
            Parameters
            x0 - mean of initial state prior
            P0 - covariance of initial state prior
            Q  - covariance matrix of the process noise 
            R  - covariance matrix of the measurement noise
            dT - discretization step for forward dynamics
        """
        self.x0=x0
        self.P0=P0
        self.Q=Q
        self.R=R
        self.dT=dT
        
        
        self.g = 9.81  # Gravitational constant
        self.l1 = 1  # Length of the pendulum 
        self.l2 = 2
        self.m1 = 1
        self.m2 = 1
        
        self.currentTimeStep = 0
        

        self.priorMeans = []
        self.priorMeans.append(None)  # no prediction step for timestep=0
        self.posteriorMeans = []
        self.posteriorMeans.append(x0)
        
        self.priorCovariances=[]
        self.priorCovariances.append(None)  # no prediction step for timestep=0
        self.posteriorCovariances=[]
        self.posteriorCovariances.append(P0)
    
    

    def stateSpaceModel(self, x, t):
        """
            Dynamics may be described as a system of first-order
            differential equations: 
            dx(t)/dt = f(t, x(t))

            Dynamics are time-invariant in our case, so t is not used.
            
            Parameters:
                x : state variables (column-vector)
                t : time

            Returns:
                f : dx(t)/dt, describes the system of ODEs
        """
    
        th1=x[0,0]
        th1d=x[1,0]
        th2=x[2,0]
        th2d=x[3,0]
        l1=self.l1
        l2=self.l2
        m1=self.m1
        m2=self.m2
        g=self.g

        th1dd=(-l2*m2*np.sin(th1-th2)*th2d**2-l1*m1*th1d-g*np.sin(th1)*(m1+m2)+m2*np.cos(th1-th2)*np.sin(th2)+m2*np.cos(th1-th2)*l1*th1d**2*np.sin(th1-th2))/(l1*(m1+m2)+l1*m2*np.cos(th1-th2)**2)
        th2dd=(l1*np.cos(th1 - th2)*(- l1*m2*np.cos(th1 - th2)*np.sin(th1 - th2)*th1d**2 + l1*m1*th1d + l2*m2*np.sin(th1 - th2)*th2d**2 - m2*np.cos(th1 - th2)*np.sin(th2) + g*np.sin(th1)*(m1 + m2)))/(l2*(l1*m2*np.cos(th1 - th2)**2 + l1*(m1 + m2))) - (l1*th1d**2*np.sin(th1 - th2))/l2 - (g*np.sin(th2))/l2
        
        dxdt=np.array([[th1d],[th1dd],[th2d],[th2dd]])
        return dxdt
    
    def discreteTimeDynamics(self, x_t):
        """
            Forward Euler integration.
            
            returns next state as x_t+1 = x_t + dT * (dx/dt)|_{x_t}
        """
        x_tp1 = x_t + self.dT*self.stateSpaceModel(x_t, None)
        return x_tp1
    

    def jacobianStateEquation(self, x_t):
        """
            Jacobian of discrete dynamics w.r.t. the state variables,
            evaluated at x_t

            Parameters:
                x_t : state variables (column-vector)
        """
        th1=x_t[0,0]
        th1d=x_t[1,0]
        th2=x_t[2,0]
        th2d=x_t[3,0]
        l1=self.l1
        l2=self.l2
        m1=self.m1
        m2=self.m2
        g=self.g
        dT=self.dT

        A = np.zeros(shape=(4,4))  

        A= np.array( [[1, dT, 0, 0],
                      [- (dT*(- l1*m2*th1d**2*np.cos(th1 - th2)**2 + l1*m2*th1d**2*np.sin(th1 - th2)**2 + l2*m2*th2d**2*np.cos(th1 - th2) + m2*np.sin(th2)*np.sin(th1 - th2) + g*np.cos(th1)*(m1 + m2)))/(l1*m2*np.cos(th1 - th2)**2 + l1*(m1 + m2)) - (2*dT*l1*m2*np.cos(th1 - th2)*np.sin(th1 - th2)*(- l1*m2*np.cos(th1 - th2)*np.sin(th1 - th2)*th1d**2 + l1*m1*th1d + l2*m2*np.sin(th1 - th2)*th2d**2 - m2*np.cos(th1 - th2)*np.sin(th2) + g*np.sin(th1)*(m1 + m2)))/(l1*m2*np.cos(th1 - th2)**2 + l1*(m1 + m2))**2, 1 - (dT*(l1*m1 - 2*l1*m2*th1d*np.cos(th1 - th2)*np.sin(th1 - th2)))/(l1*m2*np.cos(th1 - th2)**2 + l1*(m1 + m2)), (dT*(- l1*m2*th1d**2*np.cos(th1 - th2)**2 + l1*m2*th1d**2*np.sin(th1 - th2)**2 + l2*m2*th2d**2*np.cos(th1 - th2) + m2*np.cos(th2)*np.cos(th1 - th2) + m2*np.sin(th2)*np.sin(th1 - th2)))/(l1*m2*np.cos(th1 - th2)**2 + l1*(m1 + m2)) + (2*dT*l1*m2*np.cos(th1 - th2)*np.sin(th1 - th2)*(- l1*m2*np.cos(th1 - th2)*np.sin(th1 - th2)*th1d**2 + l1*m1*th1d + l2*m2*np.sin(th1 - th2)*th2d**2 - m2*np.cos(th1 - th2)*np.sin(th2) + g*np.sin(th1)*(m1 + m2)))/(l1*m2*np.cos(th1 - th2)**2 + l1*(m1 + m2))**2, -(2*dT*l2*m2*th2d*np.sin(th1 - th2))/(l1*m2*np.cos(th1 - th2)**2 + l1*(m1 + m2))],
                    [  0, 0,  1,  dT],
                    [-dT*((l1*th1d**2*np.cos(th1 - th2))/l2 - (l1*np.cos(th1 - th2)*(- l1*m2*th1d**2*np.cos(th1 - th2)**2 + l1*m2*th1d**2*np.sin(th1 - th2)**2 + l2*m2*th2d**2*np.cos(th1 - th2) + m2*np.sin(th2)*np.sin(th1 - th2) + g*np.cos(th1)*(m1 + m2)))/(l2*(l1*m2*np.cos(th1 - th2)**2 + l1*(m1 + m2))) + (l1*np.sin(th1 - th2)*(- l1*m2*np.cos(th1 - th2)*np.sin(th1 - th2)*th1d**2 + l1*m1*th1d + l2*m2*np.sin(th1 - th2)*th2d**2 - m2*np.cos(th1 - th2)*np.sin(th2) + g*np.sin(th1)*(m1 + m2)))/(l2*(l1*m2*np.cos(th1 - th2)**2 + l1*(m1 + m2))) - (2*l1**2*m2*np.cos(th1 - th2)**2*np.sin(th1 - th2)*(- l1*m2*np.cos(th1 - th2)*np.sin(th1 - th2)*th1d**2 + l1*m1*th1d + l2*m2*np.sin(th1 - th2)*th2d**2 - m2*np.cos(th1 - th2)*np.sin(th2) + g*np.sin(th1)*(m1 + m2)))/(l2*(l1*m2*np.cos(th1 - th2)**2 + l1*(m1 + m2))**2)), -dT*((2*l1*th1d*np.sin(th1 - th2))/l2 - (l1*np.cos(th1 - th2)*(l1*m1 - 2*l1*m2*th1d*np.cos(th1 - th2)*np.sin(th1 - th2)))/(l2*(l1*m2*np.cos(th1 - th2)**2 + l1*(m1 + m2)))), -dT*((g*np.cos(th2))/l2 - (l1*th1d**2*np.cos(th1 - th2))/l2 - (l1*np.sin(th1 - th2)*(- l1*m2*np.cos(th1 - th2)*np.sin(th1 - th2)*th1d**2 + l1*m1*th1d + l2*m2*np.sin(th1 - th2)*th2d**2 - m2*np.cos(th1 - th2)*np.sin(th2) + g*np.sin(th1)*(m1 + m2)))/(l2*(l1*m2*np.cos(th1 - th2)**2 + l1*(m1 + m2))) + (l1*np.cos(th1 - th2)*(- l1*m2*th1d**2*np.cos(th1 - th2)**2 + l1*m2*th1d**2*np.sin(th1 - th2)**2 + l2*m2*th2d**2*np.cos(th1 - th2) + m2*np.cos(th2)*np.cos(th1 - th2) + m2*np.sin(th2)*np.sin(th1 - th2)))/(l2*(l1*m2*np.cos(th1 - th2)**2 + l1*(m1 + m2))) + (2*l1**2*m2*np.cos(th1 - th2)**2*np.sin(th1 - th2)*(- l1*m2*np.cos(th1 - th2)*np.sin(th1 - th2)*th1d**2 + l1*m1*th1d + l2*m2*np.sin(th1 - th2)*th2d**2 - m2*np.cos(th1 - th2)*np.sin(th2) + g*np.sin(th1)*(m1 + m2)))/(l2*(l1*m2*np.cos(th1 - th2)**2 + l1*(m1 + m2))**2)), (2*dT*l1*m2*th2d*np.cos(th1 - th2)*np.sin(th1 - th2))/(l1*m2*np.cos(th1 - th2)**2 + l1*(m1 + m2)) + 1]])
 
        return A
    
    
    def jacobianMeasurementEquation(self, x_t):
        """
            Jacobian of measurement model.

            Measurement model is linear, hence its Jacobian
            does not actually depend on x_t
        """
        C = np.zeros(shape=(2,4))  
    
        C= np.array([[1, 0,0,0],[0,0,1,0]])
        return C
    

    def forwardDynamics(self):
        self.currentTimeStep = self.currentTimeStep+1  # t-1 ---> t

       
        """
            Predict the new prior mean for timestep t
        """
        
        x_t_prior_mean = self.discreteTimeDynamics(self.posteriorMeans[self.currentTimeStep-1])
        
        """
       
            Predict the new prior covariance for timestep t
        """
      
        # Linearization: jacobian of the dynamics at the current a posteriori estimate
        A_t_minus = self.jacobianStateEquation(self.posteriorMeans[self.currentTimeStep-1])

        # propagate the covariance matrix forward in time
        x_t_prior_cov = A_t_minus @ self.posteriorCovariances[self.currentTimeStep-1] @ A_t_minus.T + self.Q
        
        # Save values
        self.priorMeans.append(x_t_prior_mean)
        self.priorCovariances.append(x_t_prior_cov)
    
    
    def updateEstimate(self, z_t):
        """
            Compute Posterior Gaussian distribution,
            given the new measurement z_t
        """

        # Jacobian of measurement model at x_t
        Ct = self.jacobianMeasurementEquation(self.priorMeans[self.currentTimeStep])

        # Compute the Kalman gain matrix
        sigma= self.priorCovariances[self.currentTimeStep]
    
        K_t = sigma @ Ct.T @ np.linalg.inv(Ct @ sigma @ Ct.T + self.R)

        # Compute posterior mean
        mu= self.priorMeans[self.currentTimeStep]
        x_t_mean = mu+ K_t @ (z_t-Ct @ mu)
        

        

        # Compute posterior covariance
        x_t_cov = (np.eye(4)- K_t @ Ct) @ sigma
        
        # Save values
        self.posteriorMeans.append(x_t_mean)
        self.posteriorCovariances.append(x_t_cov)
 
    