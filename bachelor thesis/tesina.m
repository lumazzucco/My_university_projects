
clear all
clc

sim=2;              % selezione forza desiderata

global A B C kp ki s Fd  tspan  integral conFFW denP numP ud ke bre bs ks z0 d sforzo i
syms s ;
integral=1;         % se si desidera azione integrale
conFFW=1;           % se di desidera azione di feedforward

switch sim
    case 1
        Fd= 10;
    case 2
        Fd= sin(s);
    case 3
        Fd= 3*s^5;
    case 4
        Fd= atan(sin(s)*s);
    case 5
        Fd= 100*(126*s^5-420*s^6+540*s^7-315*s^8+70*s^9);
    case 6
        Fd= 1-cos(2*s);
       
end

d=sin(s);  % disturbo costante per prova

kp=1.5;
ki=3;
mrm=5; 
mre=5; 
ke=250;  
ks=100;  
brm=20;  
bre=20; 
bs=10;
A=[0 0 1 0; 0 0 0 1; -ke/mrm ke/mrm (-brm/mrm-bre/mrm) bre/mrm; ke/mre (-ke/mre-ks/mre) bre/mre (-bre/mre-bs/mre)]
B=[0; 0; 1/mrm; 0];
C=[0 ks 0 0 ];
numP= [ks*bre ke*ks];
denP= [mre*mrm (mre*brm+mre*bre+mrm*bre+bs*mrm) (mrm*ke+mre*ke+brm*bre+mrm*ks+brm*bs+bre*bs) (brm*ke+brm*ks+bre*ks+bs*ke) ks*ke];
W=tf(numP, denP);
tspan=0:0.1:20;             
x0=[0;0;0;0];             

if integral==1
 x0 =[x0;0]; C=[C 0]; % con azione integrale
end

if conFFW ==1       % con feedforward
    ud= input_ffw();
    z0=0;
    x0=[x0;z0]; 
end
i=1;                                            %x(1:4)= plant  x(5)=integral x(6)=feedforward
sforzo=[];
[t x]=ode45(@sistema_pid, tspan,x0);        %gli stati del sistema e del feedforward evolvono simultaneamente
%[t x]=ode45(@open_loop, tspan,x0);         %funzione di prova per comportamento sistema open loop
[fref fdot]= ref(t);
figure(1)
subplot(2,1,1)
sforzo(:,1:200)
if integral==1
    plot(t,C*x(:,1:5)',t,fref)
    grid
    subplot(2,1,2)
    plot(t,sforzo(:,1:201))
    grid
else
    plot(t,C*x(:,1:4)',t,fref)
    grid
    subplot(2,1,2)
    plot(t,sforzo(:,1:201))
    grid
end


if conFFW==1              %plot forza di feedforward
    if integral==1
        subplot(2,1,2)
        ffwref= (1/ks)*x(:,6);
        plot(t,ffwref)
        grid
    else
        subplot(2,1,2)
        ffwref= (1/ks)*x(:,5);
        plot(t,ffwref)
        grid
    end   
end



function dx= sistema_pid(t,x)
    global A B C kp ki Fc  integral conFFW  ks  s  ud ke bre ffw  d i sforzo
    t 
    [Fr Frdot]= ref(t);
    ffw=0;
    if conFFW==1 
        if integral==0
            Fc= C* x(1:4);
            u1= double(subs(ud,s,t));
            dz= -(ke/bre)*x(5)+(1/bre)*u1;      % sistema del feedforward
            ffw= (1/ks)*x(5);                   % uscita feedforward
        else
            Fc= C* x(1:5);
            u1= double(subs(ud,s,t));
            dz= -(ke/bre)*x(6)+(1/bre)*u1;      % sistema del feedforward
            ffw= (1/ks)*x(6);                   % uscita feedforward
        end    
    else
        Fc=C*x;
       
    end
    if integral==1
       dist= double(subs(d,s,t));           % prova con disturbo
       u= kp*(Fr-Fc)+ki*x(5)+ffw + dist;        %ingresso del sistema PI+ffw
       if u>3000
           u=3000;
       end
       dx=[A*x(1:4) + B*u; (Fr-Fc)];
       sforzo(i)=u;
    else
        u= (kp*(Fr-Fc)+ffw);
        if u>3000
           u=3000;
        end
        dx= A*x(1:4) + B*u;
        sforzo(i)=u;
    end
    if conFFW==1
        dx=[dx;dz];
    end
    i=i+1;
    
          
end

function dx= open_loop(t,x)         %per test
    global A B  Fd s
    F=double(subs(Fd,s,t));
    dx= A*x + B*F;
       
end

function [f fdot]= ref(t)               % generazione riferimenti
    global Fd s
    fd=diff(Fd,s);
    fdot= double(subs(fd,s,t));
    fref= subs(Fd,s,t);
    f=double(fref);
end

function ud= input_ffw()                    % per calcolo forza di feedforward con inversione
    global Fd s denP df1 df2 df3 df4
    f= Fd;
    df1= diff(Fd,s);
    df2= diff(df1,s);
    df3= diff(df2,s);
    df4= diff(df3,s);
    ud= simplify(denP(1)*df4 + denP(2)*df3 + denP(3)*df2 + denP(4)*df1 + denP(5)*f);

end












