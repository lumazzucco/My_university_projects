

import numpy as np
import pandas as pd

class Test:                     #data structure for DL
    def __init__(self, a, v):
        self.att= a
        self.val= v
        self.next=None
    
    def print_node(self):
        if self.att != None :
            string= str(self.att)
            string+= "(x," + self.val + ")"
            print(string + " -> Yes\n")
            print("\t|\n\t|\n")
        else:
            print(self.val)
        
class example:
    def __init__(self, id, dict):
        self.id=id
        self.values= dict
        self.output=dict["Wait"]
    

def print_ex(examples):         #for testing
    string="[ "
    for e in examples:
        string += str(e.id) + ","
    string+= "]"
    print(string)

def example_set(data):      #creates a list of objects 'example' from dictionary
    res=[]
    for key in data:
        ex= example(key,data[key])
        res.append(ex)
    return res

########################
def count_equals_aux(dic):
    res={}
    for key in dic:
        if dic[key] not in res:
            res[dic[key]]=[key]
        else:
            res[dic[key]].append(key)           # functions to create a dict like:
    return res                                  # { Attribute : { Value : [ex_id]}}

def count_equals(data):
    res={}
    for key in data:
        val= count_equals_aux(data[key])
        res[key]=val
    return res
##########################

def delete_examples(data,lis):      #deleting items from dictionary
    res=data.copy()
    list= lis.copy()
    for key in data:
        for key2 in data[key]:
            for n in lis:
                lis=list
                if n in res[key][key2]:
                    res[key][key2].remove(n)            
    return res

def delete_examples2(examples,lis):         #deleting items from list of example objects
    examples2= examples.copy()
    for e in examples2:
        if e.id in lis:  
            examples.remove(e)
    return examples

def select_attr(data):              #selecting a test that matches many examples as possible
    mass=0
    att=None
    val=None
    for key in data:
        for key2 in data[key]:
            if len(data[key][key2])>mass:
                mass= len(data[key][key2])
                att=key
                val=key2
    return Test(att,val)

def print_dl(dl):
    if dl.next==None:
        Test.print_node(dl)
    else:
        Test.print_node(dl)
        print_dl(dl.next)

def decision_list_learning(examples, data):
    if len(examples)==0:
        return Test(None,"No")
    t= select_attr(data)
    if t.att==None and t.val==None:
        print("FAILURE")
        exit()
    todelete= data[t.att][t.val]
    todelete2=todelete.copy()
    newData= delete_examples(data,todelete)
    newExamples= delete_examples2(examples, todelete2)
    t.next=decision_list_learning(newExamples, newData)
    return t


def main():
    src= pd.read_csv('restaurant_waiting.csv', header=0, index_col= None)
    data= src.to_dict(orient='index')
    examples= example_set(data)
    data2=src.to_dict(orient='dict')
    res= count_equals(data2)
    lis=res['Wait']['No']
    lis2=lis.copy()
    res= delete_examples(res,lis)
    examples= delete_examples2(examples,lis2)
    res.pop('Wait')
    print("\nthis is data:\n")
    print(res)
    print("\n-------\n")
    dl= decision_list_learning(examples, res)
    print("this is the decision list 1-DL:\n\n")
    print_dl(dl)
    print("")
  

main()

            

