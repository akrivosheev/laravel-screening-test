FROM node:16

ADD ./start.sh /
RUN chmod +x /start.sh

WORKDIR /app_
RUN wget http://ftp.de.debian.org/debian/pool/main/libp/libpng1.6/libpng-dev_1.6.37-3_amd64.deb
#RUN dpkg -i lib.deb
WORKDIR /
RUN rm -rf /app_

ENTRYPOINT [ "bash" ]
CMD ["/start.sh"]