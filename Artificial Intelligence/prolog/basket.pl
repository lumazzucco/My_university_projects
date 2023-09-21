
player(p1).
player(p2).
player(p3).
player(p4).
player(p5).
player(p6).
player(p7).
player(p8).
player(p9).
player(p10).
player(p11).
player(p12).

playmaker(p1).
playmaker(p2).
playmaker(p3).
guard(p4).
guard(p5).
guard(p6).
center(p7).
center(p8).
center(p9).
pow_forward(p10).
pow_forward(p11).
small_forward(p12).

quintet(X,Y,U,V,Z) :- playmaker(X), guard(Y), pow_forward(U), small_forward(V), center(Z).

quintet_bis(X,Y,U,V,Z) :- player(X), player(Y), player(U), player(V), player(Z),
                        X\==Y, X\==U, X\==V, X\==Z,
                        Y\==U, Y\==V, Y\==Z,
                        U\==V, U\==Z,
                        V\==Z.
                        