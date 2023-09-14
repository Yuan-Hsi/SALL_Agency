# local 端的 file structure 如下
# ----------------------------------------------------
#└── simple_flask
#    ├── Dockerfile
#    ├── myapp
#    │   ├── app.py
#    │   └── templates
#    │       └── index.html
#    └── requirements.txt

# Base image 是 python:3.7
FROM python:3.7
#FROM nvidia/cuda:10.1-cudnn7-runtime
# FROM nvidia/cuda:11.0-runtime-ubuntu20.04

# ENV TZ=Asia/Kolkata \
#     DEBIAN_FRONTEND=noninteractive


# RUN apt update && \
#     apt install --no-install-recommends -y build-essential software-properties-common && \
#     add-apt-repository -y ppa:deadsnakes/ppa && \
#     apt-get update && \
#     apt install --no-install-recommends -y python3.8 python3-pip python3-setuptools python3-distutils && \
#     python3 --version

# RUN pwd

# RUN ls

# RUN cd usr

# RUN ls && \
#     which python3.8 && \
#     ls -l /usr/bin && \
# #    update-alternatives --install /usr/bin/python3 python3 /usr/bin/python3.6 2 && \
# #    update-alternatives --install /usr/bin/python3 python3 /usr/bin/python3.8 1 && \
# #    update-alternatives --config python3 && \
# #    update-alternatives --set python3 /usr/bin/python3.8 && \
#     apt-get install gcc && \
#     # update-alternatives --config python3 
#     apt clean && rm -rf /var/lib/apt/lists/*


RUN mkdir /app

# requirements.txt 裡有我們需要的套件資訊
# 把本地端的 requirement 複製到 container中
COPY ./requirements.txt /app/requirements.txt

# 切換到container裡的 /app 路徑作為工作目錄 
WORKDIR /app

# pip是python的套件管理工具
RUN python3 --version
RUN pip3 install --upgrade pip setuptools wheel
RUN pip3 install PyPI && \
    pip3 install pyinstaller 

RUN pip3 -V && \
    pip3 install -r requirements.txt

# # 把本地端myapp資料夾複製到container的當前目錄 (/app)
COPY ./Code_Repository .
 

# 5000是我們服務所在的port
EXPOSE 5000

# 在系統中加入一個新system user 和 group，名稱皆為appuser
RUN adduser --system --group --no-create-home appuser

# 把 /app 這個directory的擁有權指定給appuser
RUN chown appuser:appuser -R --verbose /app

# 把container的 user 轉到appuser
USER appuser
# CMD代表command，當你啟動這個container時，會預設執行這個指令
CMD ["python3","test.py"]