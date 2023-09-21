
nat_numb(0).
nat_numb(s(X)) :- nat_numb(X).

plus1(0,X,X) :- nat_numb(X).
plus1(s(X),Y,s(Z)) :- plus1(X,Y,Z).

times(s(0),X,X) :- nat_numb(X).
times(s(X),Y,Z) :- times(X,Y,W), plus1(W,Y,Z). 

power(0,X,s(0)).
power(s(0),X,X) :- nat_numb(X).
power(s(X),Y,Z) :- power(X,Y,W), times(W,Y,Z).

factorial(0,s(0)).
factorial(s(X),Y) :- factorial(X,Z), times(Z,s(X),Y).

minimum(0,X,0).
minimum(X,0,0).
minimum(s(X), s(Y), s(Z)):- minimum(X,Y,Z).

suffix( [X|[]], X ) .
suffix( [X|Xs],Y) :- suffix(Xs,Y).