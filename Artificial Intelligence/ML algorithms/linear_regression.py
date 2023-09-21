
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
    der= -2*x[i]*(y-round(sum,0))
    return der

def batch(data,k):                      # building random batch
    b=np.random.randint(0,74,size=k)
    d={}
    n=0
    for i in b:
        d[n]=data[i]
        n+=1
    return d

def split_data(data):
    trainset={}
    testset={}
    i=0
    j=0
    for key in data:
        if key<75:
            trainset[i]=data[key]
            i+=1
        else:
            testset[j]=data[key]
            j+=1
    return (trainset,testset)
    

def cont(v,lim):
    ok=False
    for n in v:
        ok= ok or (abs(n)>lim)
    return ok

def SGD(k,learning_rate,max,data):              # Stochastic Gradient Descent
    w=[1,1,1,1,1]        # random initial point of parameter space
    i=0
    dvloss=[1000,1000,1000,1000,1000]       # partial derivatives vector
    while  cont(dvloss,0.1) and i<max:      # while not every partial derivative is < 0.01
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
        score[key]={'desired': b[key]['target'], 'computed': round(sum,0)}
    return score


def main():
    data = load_iris(as_frame=True)
    data= data.frame.to_dict(orient='index')
    print(data)
    (trainset,testset)= split_data(data)
    w= SGD(15,0.0001,100000000,trainset)
    score=test(w,testset,50)
    print("\nScore Linear Regression (less iterations but with some uncertainties):\n")
    print(score)
    print("\n")

main()
