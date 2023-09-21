
import numpy as np
import pandas as pd
import math
from sklearn.datasets import load_iris

def dLoss(i,w,data,y):                  # computing dLoss/dwi
    sum=0
    x=[1]
    for key in data:
        x.append(data[key])
    for k in range(0,len(w)):
        sum+= w[k]*x[k]
    h= 1/ (1+np.exp(-sum))
    der= (h-y)*x[i]
    return der

def batch(data,k):                      # building random batch
    b=np.random.randint(0,150,size=k)
    d={}
    n=0
    for i in b:
        d[n]=data[i]
        n+=1
    return d

def cont(v,lim):
    ok=False
    for n in v:
        ok= ok or (abs(n)>lim)
    return ok

def SGD(k,learning_rate,max,data):              # Stochastic Gradient Descent
    w=[0.01,0,0,0,0]        # random initial point of parameter space
    i=0
    dvloss=[1000,1000,1000,1000,1000]       # partial derivatives vector
    while  cont(dvloss,0.01) and i<max:      # while not every partial derivative is < 0.01
       # print(dvloss)
        b= batch(data,k)   
       # print(b)
        for n in range(0,5):
            dloss=0
            for m in range(0,k):
                l= dLoss(n,w,b[m],b[m]['target'])   # sum of every dLoss/dwi related to all samples in given batch
                dloss+= l
            w[n]-= learning_rate * dloss        # update
            dvloss[n]=dloss
        i+=1
    #print(dvloss)
    print("\nIterations: "+str(i))
    return w

def test(w,data,n):
    b=batch(data,n)
    score={}    
    for key in b:
        x=[1]
        for key2 in b[key]:
            x.append(b[key][key2])
        sum=0
        for k in range(0,len(w)):
            sum+= w[k]*x[k]
        h= 1/ (1+np.exp(-sum))      #logistic function
        score[key]={'desired': b[key]['target'], 'computed': h}
    return score

def main():
    data = load_iris(as_frame=True)
    data= data.frame.to_dict(orient='index')
    for key in data:                    # rearranging dataset
        if data[key]['target']!= 0:
            data[key]['target']=1
    w= SGD(20,0.001,100000,data)
    score=test(w,data,20)
    print("\nScore Logistic Regression( more iterations but much more precise):\n")
    print(score)
    print("\n")

main()