FROM node:16 AS base

WORKDIR /var/www/html/app

CMD ["npm", "run", "dev"]
