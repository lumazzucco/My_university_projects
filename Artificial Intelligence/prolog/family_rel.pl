mother(antonella,ludovica).
father(marco,ludovica).
mother(wilma,antonella).
father(ruggero,antonella).
mother(marcella,marco).
father(fausto,marco).
brother(sergio,antonella).
sister(antonella,sergio).
sister(stefania,antonella).
sister(antonella,stefania).
sister(miriam,marco).
brother(marco,miriam).
mother(miriam,alessia).

grandfather(X,Y) :- father(X,Z), parent(Z,Y).
grandmother(X,Y) :- mother(X,Z), parent(Z,Y).

cousin(X,Y) :- parent(Z,X),sibling(Z,V),parent(V,Y).

sibling(X,Y) :- sister(X,Y).
sibling(X,Y) :- brother(X,Y).

parent(X,Y) :- mother(X,Y).
parent(X,Y) :- father(X,Y).

aunt(X,Y) :- sister(X,Z), parent(Z,Y).
uncle(X,Y) :- brother(X,Z), parent(Z,Y).
