
import numpy as np
import pandas as pd
import math
from sklearn.datasets import load_iris

class Node:
    def __init__(self,v, i, o,n):
        self.name=n
        self.value=v
        self.in_edges= i
        self.out_edges= o
        self.delta=0
    
    def add_edge_in(self,a,e):
        self.in_edges.append(e)
    
    def add_edge_out(self,b,e):
        self.out_edges.append(e)

class Edge:
    def __init__(self,a,b,w):
        self.da= a
        self.to= b
        self.weight=w

class NeuralNetwork:
    def __init__(self,input,hidden,output):
        self.layers= {'input':input, 'hidden':hidden, 'output':output}

def connect_nodes(a,b,w):
    e=Edge(a,b,w)
    a.add_edge_out(b,e)
    b.add_edge_in(a,e)

def generateNN(n_input, n_output, n_neurons):       # n_neurons=[10 20 30]
    input_layer= []
    k=1
    for i in range(0,n_input):
        n= Node(None,[],[],k)
        k+=1
        input_layer.append(n)
    bias= Node(1,[],[],0)
    input_layer.append(bias)
    hidden_layers=[]
    for i in n_neurons:
        hidden=[]
        for j in range(0,i):
            n= Node(None,[],[],k)
            k+=1
            hidden.append(n)
        bias= Node(1,[],[],0)
        hidden.append(bias)
        hidden_layers.append(hidden)
    output_layer=[]
    for i in range(0,n_output):
        n= Node(None,[],[],k)
        k+=1
        output_layer.append(n)
    NN= NeuralNetwork(input_layer,hidden_layers,output_layer)
    for node in NN.layers['input']:                             # creating edges input-first hidden layers
        for  node2 in NN.layers['hidden'][0]:
            if node.name==0 and node2.name==0:
                continue
            elif node.name==0:
                connect_nodes(node,node2,1)
            else:
                connect_nodes(node,node2,np.random.uniform(-1,1))
    i=0
    j=1
    while j<len(n_neurons):                                     # creating edges among hidden layers
        for node in NN.layers['hidden'][i]:
            for node2 in NN.layers['hidden'][j]:
                if node.name==0 and node2.name==0:
                    continue
                elif node.name==0:
                    connect_nodes(node,node2,1)
                else:
                    connect_nodes(node,node2,np.random.uniform(-0.1,0.1))
        i+=1
        j+=1
    for node in NN.layers['hidden'][-1]:                            # creating edges last hidden-output layers
        for node2 in NN.layers['output']:
            if node.name==0:
                connect_nodes(node,node2,1)
            else:
                connect_nodes(node,node2,np.random.uniform(-1,1))
    return NN

def set_input(data, network):           # data= {'sepal':0.1,'sepal':4,'petal':0.2,'petal':0.3}
    input= network.layers['input']
    val= list(data.values())
    i=0
    for node in input:
        node.value=val[i]
        i+=1

def relu(x):
    return np.max([0,x])

def Drelu(x):               #derivative of relu (heaviside step function)
    return np.max([0,1])

def activation(n):
    sum=0
    for edge in n.in_edges:
        sum+= edge.da.value*edge.weight
    sum=relu(sum)
    n.value=sum

def forward_pass(network):
    hiddens= network.layers['hidden']
    output= network.layers['output']
    for layer in hiddens:
        for node in layer:
            activation(node)
    for node in output:
        activation(node)

def backpropagation(network, ydesired):
    l= list(network.layers.values())     # [output,[hiddens],input]
    layers=[]
    layers.append(l[-1])
    for lyr in l[1]:
        layers.insert(1,lyr)
    layers.append(l[0])
    i=0
    for layer in layers:
        if i== len(layers)-1:   #input layer
            break
        for node in layer:
            if node.name==0:    # bias node
                continue
            if i==0:            # output layer
                node.delta= -(ydesired-node.value)*Drelu(node.value)
            else:               # hidden layer(s)
                error=0
                for edge in node.out_edges:
                    error+= Drelu(node.value)*edge.weight*edge.to.delta
                node.delta=error
        i+=1    # next layer

def weights_update(network, learning_rate):
    l= list(network.layers.values())
    layers=[]
    layers.append(l[0])
    for j in l[1]:
        layers.append(j)
    layers.append(l[-1])
    i=0
    for layer in layers:
        if i==0 :           #input layer
            i+=1
            continue
        for node in layer:
            if node.name==0:        #bias node
                continue
            for edge in node.in_edges:
                if edge.da.name==0:     #bias node
                    continue
                edge.weight-= learning_rate*node.delta*edge.da.value
               # print("edge.weight: " + str(edge.weight))
        i+=1

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

def get_result(network):
    return network.layers['output'][0].value

def print_weights(network):
    w=[]
    hidden= network.layers['hidden']
    output= network.layers['output']
    for node in hidden:
        for edge in node.in_edges:
            w.append(edge.weight)
    for node in output:
        for edge in node.in_edges:
            w.append(edge.weight)
    print("w: "+str(w))
    

def train(network, epochs, trainset):
    error=2
    e=1
    done= abs(error)<=1.5
    #for e in range(epochs):
    while not done:
        error=0
        for key in trainset:
            set_input(trainset[key],network)
            forward_pass(network)
            backpropagation(network,trainset[key]['target'])
            weights_update(network,0.001)
            error+= (trainset[key]['target']- get_result(network))**2
        print("epoch: "+ str(e)+ " mse: " + str(error))
        e+=1
        done= abs(error)<=1

def test(network, testset):
    for key in testset:
        set_input(testset[key],network)
        forward_pass(network)
        print("test "+str(key) + " -> " + "desired: "+ str(testset[key]['target']) + " computed: " + str(round(get_result(network),1) )) 



def main():
    data = load_iris(as_frame=True)
    data= data.frame.to_dict(orient='index')
    (trainset,testset)= split_data(data)
    network= generateNN(4,1,[4,4,4])
    print("\nTRAIN\n")
    train(network,100,trainset)
    print("\nTEST\n")
    test(network,testset)

main()



        
