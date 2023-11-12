from DDPG_TD3_Infrustructure import TD3,ReplayBuffer ,evaluate_policy
import Data_baseket
import importlib
import torch
import numpy as np
import time
import os

def Train(account = "Guest",agent = "testing",price_key = '收盤價(元)',train_potion = 80,seed = 42,start_timesteps = 1e4,eval_freq = 5e3,max_timesteps = 30000,expl_noise = 0.1,
          discount = 0.99, tau = 0.005, policy_noise = 0.2,noise_clip = 0.5,policy_freq = 2, reward_driver= 0.05, punish_driver = 0.05, length = 100, stock_num = 1000, 
          interest_rate = 0.05,fee_rate = 0.02):

    # ------------------------------------- Data and Env ---------------------------------------

    # 取資料
    data_and_space =  Data_baseket.get_data(account,agent,price_key)
    data = data_and_space.data
    filters = data_and_space.filters
    og_data = data_and_space.og_data
    train_amount = int(data.shape[0]*train_potion/100)
    least_amount = int(data.shape[0]*(100-train_potion)/100/2) # 除 2 是為了分成驗證與測試集
    train_data = data[:train_amount]
    val_data = data[train_amount:train_amount+least_amount]
    test_data = data[train_amount+least_amount:]
    space_dict = data_and_space.space_dict

    # 取環境
    name = "custom_env"+"."+account+"_"+agent+".ETFenv"
    module_name, class_name = name.rsplit('.', 1)
    module = importlib.import_module(module_name)
    desired_class = getattr(module, class_name)
    env = desired_class(data = train_data, space_dict=space_dict, price_key = price_key, reward_driver = reward_driver, punish_driver = punish_driver, length = length, stock_num = stock_num, interest_rate = interest_rate, fee_rate=fee_rate,seed = seed, filters = filters,og_daata = og_data)
    
    name = "custom_env.General_Performance.ETFenv"
    module_name, class_name = name.rsplit('.', 1)
    module = importlib.import_module(module_name)
    desired_class = getattr(module, class_name)
    length = len(val_data)-2
    evaluate_env = desired_class(data = val_data, space_dict=space_dict, price_key = price_key, reward_driver = reward_driver, punish_driver = punish_driver, length = length, stock_num = stock_num, interest_rate = interest_rate, fee_rate=fee_rate, seed = seed, filters = filters,og_daata = og_data)
    length = len(test_data)-2
    testing_env = desired_class(data = test_data, space_dict=space_dict, price_key = price_key, reward_driver = reward_driver, punish_driver = punish_driver, length = length, stock_num = stock_num, interest_rate = interest_rate, fee_rate=fee_rate, seed = seed, filters = filters,og_daata = og_data)
    # ------------------------------------- Initilize ---------------------------------------

    # parameeter setting
    env_name = account+"_"+agent # Name of a environment (set it to any Continous environment you want
    file_name = "%s_%s" % ("TD3", env_name)
    # start_timesteps = 1e4 Number of iterations/timesteps before which the model randomly chooses an action, and after which it starts to use the policy network 隨機探索步數
    # eval_freq = 5e3 How often the evaluation step is performed (after how many timesteps) 多少步後做一次評估
    # max_timesteps = 30000 Total number of iterations/timesteps 總共訓練步數
    save_models = False # Boolean checker whether or not to save the pre-trained model 儲存模型
    # expl_noise = 0.1 Exploration noise - STD value of exploration Gaussian noise 探索的動作噪訊 
    batch_size = env.length # Size of the batch 訓練批次量
    # discount = 0.99  Discount factor gamma, used in the calculation of the total discounted reward 報酬遞減因子
    # tau = 0.005 Target network update rate 目標模型更新率
    # policy_noise = 0.2 STD of Gaussian noise added to the actions for the exploration purposes policy 網路動作噪訊
    # noise_clip = 0.5 Maximum value of the Gaussian noise added to the actions (policy) 動作噪訊最大值
    # policy_freq = 2  Number of iterations to wait before the policy network (Actor model) is updated 多少 iteration 後，更新 policy 策略網路

    # We set seeds and we get the necessary information on the states and actions in the chosen environment
    env.seed(seed)
    env.action_space.np_random.seed(seed)
    torch.manual_seed(seed)
    np.random.seed(seed)
    state_dim = len(env.observation_space)
    action_dim = env.action_space.shape[0]
    max_action = float(env.action_space.high[0])

    # We create the policy network (the Actor model)
    policy = TD3(state_dim, action_dim, max_action,seed)

    # Memory pool
    replay_buffer = ReplayBuffer(seed = seed)

    # Evaluation list
    evaluations = [evaluate_policy(env = evaluate_env,policy = policy)]

    # We initialize the variables
    total_timesteps = 0
    timesteps_since_eval = 0
    episode_num = 0
    done = True
    t0 = time.time()

    py_name = env_name+".txt"
    filename = os.path.join("../File_Repository/training_log", py_name)

    with open(filename,"a") as f:
        f.write("----------------------  全新一次超參數測試： ----------------------\n")
        f.write("本次超參數自動調整設定為：\n")
        f.write("總共訓練步數 :%d 隨機探索步數 :%d 報酬遞減因子 :%.2f \n" %(max_timesteps,start_timesteps,discount))
        f.write("探索動作噪訊 :%.2f 代理人動作噪訊 :%.2f 目標模型每輪更新率 :%.4f \n\n" %(expl_noise,policy_noise,tau))
        f.close()
    # ------------------------------------- Training ---------------------------------------

    # We start the main loop over 500,000 timesteps
    max_episode_steps = env._max_episode_steps
    env_og = env
    while total_timesteps < max_timesteps:

        # If the episode is done
        if done:

            # If we are not at the very beginning, we start the training process of the model
            if total_timesteps != 0 :
                text = "Total Timesteps: %d     Episode Num: %d     Reward: %.2f \n" % (total_timesteps, episode_num, episode_reward)
                with open(filename, "a") as f:
                    f.write(text)
                    f.close()

                policy.train(replay_buffer, episode_timesteps, batch_size, discount, tau, policy_noise, noise_clip, policy_freq)

            # We evaluate the episode and we save the policy
            if timesteps_since_eval >= eval_freq:
                timesteps_since_eval %= eval_freq
                performance,action_arr,avg_action = evaluate_policy(env = evaluate_env,policy = policy)
                with open(filename, "a") as f:
                    f.write("--------------------------------------------------\n")
                    f.write("Avg. Action over the Evaluation Step: %.5f \n" % (avg_action))
                    f.write("Reward over the Evaluation Step: %.5f \n" % (performance))
                    f.write("--------------------------------------------------\n")
                    f.close()

                evaluations.append(performance)
                policy.save(file_name, directory="../Model_Repository/temp") # 先將 policy 暫存，若是最佳模型才會存到 pytorch_models
                # np.save("../Model_Repository/results/%s" % (file_name), evaluations) 因 training log 已存在 File_Repository

            # When the training step is done, we reset the state of the environment
            obs = env.reset()

            # Set the Done to False
            done = False

            # Set rewards and episode timesteps to zero
            episode_reward = 0
            episode_timesteps = 0
            episode_num += 1

        # Before 10000 timesteps, we play random actions
        if total_timesteps < start_timesteps:
            action = env.action_space.sample()
            
        else: # After 10000 timesteps, we switch to the model
            # 這邊加 wrapper autoscale action
            action = policy.select_action(np.array(obs))
        # If the explore_noise parameter is not 0, we add noise to the action and we clip it
            if expl_noise != 0:
                action = (action + np.random.normal(0, expl_noise, size=env.action_space.shape[0])).clip(env.action_space.low, env.action_space.high)
        # The agent performs the action in the environment, then reaches the next state and receives the reward
        new_obs, reward, done, info = env.step(action)
        action = info['action']
        #print("action: %f, reward: %f" %(action,reward))
        # We check if the episode is done
        done_bool = 0 if episode_timesteps + 1 == max_episode_steps else float(done)

        # We increase the total reward
        episode_reward += reward

        # We store the new transition into the Experience Replay memory (ReplayBuffer)
        replay_buffer.add((obs, new_obs, action, reward, done_bool))

        # We update the state, the episode timestep, the total timesteps, and the timesteps since the evaluation of the policy
        obs = new_obs
        episode_timesteps += 1
        total_timesteps += 1
        timesteps_since_eval += 1

    # ------------------------------------- Evalute ---------------------------------------

    import matplotlib.pyplot as plt
    from matplotlib import cm
    from matplotlib.collections import LineCollection
    import io
    import base64

    pic_IObytes = io.BytesIO()

    price_list = data_and_space.price.tolist()[-len(test_data):]
    n_list = [x for x in range(len(test_data))]
    performance,action_arr,avg_action = evaluate_policy(env = testing_env,policy = policy)
    print(action_arr)

    price_list.pop()
    n_list.pop()
    # 製作figure
    fig = plt.figure()

    #圖表的設定
    ax = fig.add_subplot(1, 1, 1)

    #直線圖
    ax.plot(n_list,price_list, color='grey',linewidth=0.5,label='price trend')
    #散佈圖
    ax.scatter(n_list, price_list, c=action_arr, cmap='coolwarm',s=3)

    plt.title('Evaluataion')
    plt.ylabel('Price')
    plt.xlabel('Day')
    plt.legend()
    #plt.savefig(loss_chart_path)
    plt.savefig(pic_IObytes,  format='png')
    plt.close(fig)
    pic_IObytes.seek(0)
    pic_hash = base64.b64encode(pic_IObytes.read())
    new_encoded = str(pic_hash)
    new_encoded = new_encoded[2:-1]
    evaluation_uri = 'data:image/png;base64,{}'.format(new_encoded)

    
    with open(filename, "a") as f:
        f.write("--------------------------------------------------\n")
        f.write("Avg. Action over the FINAL TEST PHASE: %.5f \n" % (avg_action))
        f.write("Reward over the FINAL TEST PHASE: %.5f \n" % (performance))
        f.write("--------------------------------------------------\n")
        f.close()
    return -performance,evaluation_uri,fig
    # 紅色為正值 藍色為負值
    # print('本次測試結果，將會賺得：' + str(money-100000) + '元(本金為100000)')

if __name__ == '__main__':
    Train()
