
subst(This, That, MyStr, Result) :- append(This,After,Rest),
                                    append(Before,Rest,MyStr),
                                    !,
                                    subst(This,That,After,AfterResult),
                                    append([Before,That,AfterResult], Result).
subst(_,_,S,S).

safe([]).
safe( [Queen|OtherQueens]) :- safe(OtherQueens), noattack(Queen,OtherQueens,1).

noattack(_,[],_).
noattack(Q, [Q1 | Qlist], Qdist) :- abs(Q1-Q) =\= Qdist,
                                    Q1 \== Q,
                                    Dist1 is Qdist+1,
                                    noattack(Q,Qlist,Dist1).

% heuristic function: how many queens the first one does not attack (success if 3)
n_safe(_,[],_,N,N).
n_safe(Q, [Q1 | Qlist], Qdist, N, M) :- ( abs(Q1-Q) =\= Qdist, Q1 \== Q, N2 is N+1; N2 is N ),
                                    Dist1 is Qdist+1,
                                    n_safe(Q,Qlist,Dist1,N2, M).

h( [Queen|OtherQueens], N) :- n_safe(Queen, OtherQueens, 1,0,N).  

% functions to initialize population with two members
new_member(X) :- length(X,4), init(X,s(s(s(s(0))))).

init(_,0).
init([X|Xs], s(Y)) :- init(Xs,Y), random_between(1,4,R), X is R.

new_population(X):- new_pop(X).
new_pop([X,Y]) :- new_member(X), new_member(Y).

% auxiliary functions for lists
take_element([X|_],1,X).        
take_element(_,0,_):- !,fail.
take_element([_|Xs],Index,El):- IndexNew is Index-1, 
                            take_element(Xs,IndexNew,El).

put_element([_|Xs],1,El, [El|Xs]).      
put_element([X|Xs], Index, El, [Y|Ys]) :- IndexNew is Index-1, Y is X, put_element(Xs, IndexNew, El, Ys).

random2(1,2,1,2).       % returns two -different- random elements
random2(A,B,R1,R2):- random_between(A,B,R1), random_between(A,B,R2), (R1\==R2 ;!, C is A, D is B, random2(C,D,_,_) ).

random2bis(A,B,R1,R2):- random_between(A,B,R3),                         %returns random element other than one given
                        ( R3=\=R1, R2 is R3 ; random2bis(A,B,R1,R2)).

equal([],[]).                               % true if List1 == List2
equal([X|Xs],[Y|Ys]):- X==Y, equal(Xs,Ys).

% functions for crossover
first_half(List,Half):- take_element(List,1,X),take_element(List,2,Y),
                        append([X],[Y],Half).

second_half(List,Half):- take_element(List,3,X),take_element(List,4,Y),
                        append([X],[Y],Half).

crossing(Memb1,Memb2, Child):- first_half(Memb1, Half1), second_half(Memb2, Half2),
                                append(Half1,Half2,Child).

% functions for mutation
are_equals(_,5,_):- fail.                   % returns the index of the element that has duplicates in a given list
are_equals(List,I, N):- take_element(List,I,El), delete(List,El,List2), 
                        length(List2,L), 
                        ( L<3, N is I; I2 is I+1, are_equals(List,I2,N)).

equals(_,5):- fail.                  % true if a list has duplicates
equals(List,I):- take_element(List,I,El), delete(List,El,List2), 
                length(List2,L), ( L<3; I2 is I+1, equals(List,I2)).

different(List, Res) :- are_equals(List,1,N), take_element(List,N,El),     % Res is List with no duplicates
                            random2bis(1,4,El,R), 
                            put_element(List,N,R,Res).

stop(X,X).          % changes elements in a list untill it has no duplicates in it
verify(List,Res) :- not(equals(List,1)), stop(List,Res); different(List,R), verify(R,Res).

mutation(X, NewX) :- random2(1,4,R1,R2), take_element(X,R1,El),   % randomically changes an element and tests if all elements are different
                        subst([El], [R2], X, Y), verify(Y,NewX). 

% function for evaluation

% evaluate(_,0,_):- !,fail.   %tests if a member of the population is solution
% evaluate(Population, Len, Winner):- take_element(Population,Len,Winner), (safe(Winner); Len2 is Len-1, evaluate(Population, Len2, _) ).

evaluate2(Population, Len, Winner) :- take_element(Population,Len,Winner), safe(Winner).  

% functions for selection
best1([],Best,_, Best).
best1([First|Others], Best, H, Res) :- h(First,N), ( N>H, best1(Others, First, N, Res) ; best1(Others, Best, H,Res)).

selection(Population, Memb1, Memb2) :- best1(Population, _, 0, Memb1), reverse(Population, PopulationRev), delete(PopulationRev, Memb1, Population2),
                                        best1(Population2,_,0,Memb2).

% main functions
grow_population(Ancestors, NewPopulation) :- selection(Ancestors,Memb1,Memb2),
                                            crossing(Memb1,Memb2,Child),
                                            mutation(Child, NewChild),
                                            append(Ancestors,[NewChild],NewPopulation).

search_sol(Pop,Sol) :- grow_population(Pop,Y), length(Y,Len),
                                 (evaluate2(Y,Len,Sol) ; search_sol(Y,Sol) ).

four_queens(Solution) :- new_population(X),
                            search_sol(X,Solution)  .



                                       
                        

