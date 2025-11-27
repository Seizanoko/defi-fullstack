# Build stage
FROM node:22-alpine AS base

WORKDIR /app

# Copy dependency files
COPY package*.json ./

# ---------------------------------------------------------------------------
# Build stage
# ---------------------------------------------------------------------------
FROM base AS build

# Install all dependencies required for building
RUN npm ci

# Copy the full source tree (app code + build config)
COPY . .

# Run the production build
RUN npm run build

# ---------------------------------------------------------------------------
# Production stage 
# ---------------------------------------------------------------------------
FROM nginx:stable-alpine AS production

# Copy compiled static assets from the build stage
COPY --from=build /app/dist /usr/share/nginx/html

EXPOSE 80
CMD ["nginx", "-g", "daemon off;"]
