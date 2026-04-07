FROM node:22-alpine
WORKDIR /app
COPY package.json package-lock.json* ./
RUN npm install --omit=dev
COPY . .
RUN mkdir -p /app/data
EXPOSE 4000
CMD ["npm", "start"]
