import Data_preprocess
import numpy as np
import json

if __name__ == '__main__':
    data,space = Data_preprocess.data_preprocess('bruno','Guest')
    np.save("/Users/oreo/Desktop/SALL_Agency/RL/File_Repository/test", data)
    print(data.shape)
    print(space)
