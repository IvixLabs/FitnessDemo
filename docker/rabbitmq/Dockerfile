FROM rabbitmq:3.7-management
RUN apt-get update && apt-get install -y curl unzip
RUN curl https://dl.bintray.com/rabbitmq/community-plugins/3.7.x/rabbitmq_delayed_message_exchange/rabbitmq_delayed_message_exchange-20171201-3.7.x.zip > /plugins/rabbitmq_delayed_message_exchange-20171201-3.7.x.zip
RUN unzip /plugins/rabbitmq_delayed_message_exchange-20171201-3.7.x.zip -d /plugins/
RUN rabbitmq-plugins enable --offline rabbitmq_delayed_message_exchange