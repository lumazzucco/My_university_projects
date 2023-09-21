

import numpy as np
import pandas as pd
import math as m

class tree:
    def __init__(self, att):
        self.root= att
        self.branches= []
        
    def get_branches(self):
        return self.branches

    def get_branch(self, label):
        for Branch in self.branches:
            if Branch.label==label:
                return Branch
    
    def add_branch(self,label,link):
        Branch= branch( link,label)
        self.branches.append(Branch)
    

def print_tree_aux(Tree,i):
        if len(Tree.branches)==0:
            print( "-> "+Tree.root)
        else:
            print(" ...")
            print("\n"+str(i)+ " - ( " + Tree.root + " ) :")
            for b in Tree.branches:
                print("\t\t["+b.label+"]", end='')
                print_tree_aux(b.totree,i+1)
    
def print_tree(Tree):
    print_tree_aux(Tree,0)

'''
PATTERN DEL PRINT

(livello)- (nome attributo):  [label valore]-> risposta
                              [label valore] ... path segue al nodo successivo

'''
    

class attribute:
    def __init__(self, att,values):
        self.name= att
        self.values=values
    
    def set_name(self,a):
        self.name=a
    
    def get_name(self,a):
        return self.name
    
    def set_values(self,v):
        self.values=v
    
    def get_values(self):
        return self.values
    

class branch:
    def __init__(self, Tree, label):
        self.label=label
        self.totree=Tree
    
    def get_label(self):
        return self.label

    def set_label(self,label):
        self.label=label

    def link(self,Tree):
        self.totree=Tree
    
class example:
    def __init__(self, id, dict):
        self.id=id
        self.values= dict
        self.output=dict["Wait"]
    
def example_set(data):
    res=[]
    i=1
    for key in data:
        ex= example(i,data[key])
        res.append(ex)
        i= i+1
    return res

def plurality_value(examples):
    y= ("Yes",0)
    n= ("No",0)
    for el in examples:
        x= el.output
        if x=="Yes" :
            y=(y[0],y[1]+1)
        else:
            n=(n[0],n[1]+1)
    if y[1]>n[1]:
        return y[0]
    else:
        return n[0]

def partition(examples):
    res=(0,0)   #(yes,no)
    for el in examples:
        x= el.output
        if x=="Yes" :
            res=(res[0]+1,res[1])
        else:
            res=(res[0],res[1]+1)
    return res


def same_classification(examples):
    res= True
    for i in range (0,len(examples)-1):
        x= examples[i].output
        y= examples[i+1].output
        res= res and x==y
    return res

def examples_subset(examples, value, id):
    res=[]
    for e in examples:
        if e.values[id]==value:
            res.append(e)
    return res

def B(q):
    res=0
    if q!=0 and q!=1:
        res= -( q * m.log2(q) + (1-q) * m.log2(1-q) )
    elif q==0:
        res= -( (1-q) * m.log2(1-q) )
    else:
        res= -( q * m.log2(q))
    return res

def importance(attribute, examples):
    (p,n)= partition(examples)
    q= p/(p+n)
    Bg= B(q)
    Br=0
   # print(attribute.name+ " ="+str(Bg),end=' ')
    for v in attribute.values:
        subset= examples_subset(examples,v,attribute.name)
        (pk,nk)= partition(subset)
       # print(attribute.name+" p="+str(p)+" n="+str(n)+" pk="+str(pk)+" nk="+str(nk))
        if (pk+nk)!=0:
            qk=pk/(pk+nk)
        else:
            qk=0
        x=pk+nk
        y=p+n
        res=x/y
        #print(str(res))
        Br+=  res * B(qk)
        #print("gain: "+str(Bg-Br))
    return Bg-Br

def most_important(attributes,examples):
    mass= importance(attributes[0],examples)
    att=attributes[0]
    for a in attributes:
        imp= importance(a,examples)
       # print(a.name+ " "+str(imp))
        if imp > mass:
            mass= imp
            att=a
   # print(att.name)
    return att

def remove_item(attributes,id):
    new=[]
    for a in attributes:
        if a.name!=id:
            new.append(a)
    return new


def learn_decision_tree( examples, attributes, parent_examples):
    if len(examples)==0:
        return tree(plurality_value(parent_examples))
    elif same_classification(examples):
        return tree(examples[0].output)
    elif len(attributes)==0:
        return tree(plurality_value(examples))
    else:
        A= most_important(attributes,examples)
        treeF= tree(A.name)
        for v in A.values:
            exs= examples_subset(examples,v,A.name)
            new_attributes=remove_item(attributes,A.name)
            subtree= learn_decision_tree( exs, new_attributes, examples)
            treeF.add_branch(v,subtree)
        return treeF

def attribute_set(list):
    att=[]
    val=[["Yes", "No"],
    ["Yes", "No"],
    ["Yes", "No"],
    ["No", "Yes"],
    ["None", "Some","Full"],
    ["$", "$$", "$$$"],
    ["Yes", "No"],
    ["Yes", "No"],
    ["French", "Italian", "Thai", "Burger"],
    ["0-10", "10-30", "30-60", ">60"]]
    for i in range(0,len(list)):
        a= attribute(list[i],val[i])
        att.append(a)
    return att

def main():
    src= pd.read_csv('restaurant_waiting.csv', header=0, index_col= None)
    data= src.to_dict(orient='index')
    examples= example_set(data)
    attlist=["Alt","Bar","Fri","Hun","Pat","Price","Rain","Res","Type","Est"]
    attributes= attribute_set(attlist)
    Tree=learn_decision_tree( examples, attributes, [])
    print_tree(Tree)

main()