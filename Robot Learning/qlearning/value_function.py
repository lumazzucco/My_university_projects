
import numpy as np
from matplotlib import pyplot as plt


q_grid= np.load('q_values.npy')
discr= 16

v_grid= np.zeros((discr,discr,discr,discr))

for x in range(discr):
    for th in range(discr):
        for v in range(discr):
            for av in range(discr):
                state= (x,th,v,av)
                v_grid[state]=  np.max([q_grid[x,th,v,av,0], q_grid[x,th,v,av,1]])

# average over av
vplot_av= np.zeros((discr,discr,discr))

for x in range(discr):
    for th in range(discr):
        for v in range(discr):
            vplot_av[x,th,v] = np.mean(v_grid[x,th,v])

# average over v
vplot= np.zeros((discr,discr))

for x in range(discr):
    for th in range(discr):
        vplot[x,th] = np.mean(vplot_av[x,th])

fig,ax= plt.subplots()
ax= plt.imshow(vplot)
plt.title('V function heatmap')
plt.xlabel('theta')
plt.ylabel('x')
plt.colorbar()
plt.show()
