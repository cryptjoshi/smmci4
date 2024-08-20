FROM node:20.15-bookworm-slim AS base
ENV NEXT_TELEMETRY_DISABLED=1
RUN yarn global add turbo@2

# Installer grabs the files we need, then installs the dependencies
FROM base AS installer
ENV NEXT_TELEMETRY_DISABLED=1
WORKDIR /app
COPY . .
RUN npm ci
RUN turbo run build

# Runner copies everything from the installer, then runs the app as a locked
# down nextjs user
FROM node:20.15-bookworm-slim AS runner
WORKDIR /app
# ca-certificates is needed for 1Password CLI
RUN apt-get update && apt-get install -y ca-certificates --no-install-recommends && rm -rf /var/lib/apt/lists/*
RUN groupadd -r nodejs && useradd -r -g nodejs -d /app -s /sbin/nologin nextjs \
    && chown -R nextjs:nodejs /app

# Copy in the built files. Automatically leverage output traces to reduce image
# size: https://nextjs.org/docs/advanced-features/output-file-tracing
COPY --chown=nextjs:nodejs --from=installer /app/.env.production .
COPY --chown=nextjs:nodejs --from=installer /app/next.config.mjs .
COPY --chown=nextjs:nodejs --from=installer /app/package.json .
COPY --chown=nextjs:nodejs --from=installer /app/.next/standalone ./
COPY --chown=nextjs:nodejs --from=installer /app/.next/static ./apps/app/.next/static
COPY --chown=nextjs:nodejs --from=installer /app/public ./apps/app/public

# Include the 1Password CLI tool, which we use to fetch secrets at runtime
COPY --chown=nextjs:nodejs --from=1password/op:2 /usr/local/bin/op /usr/local/bin/op

USER nextjs

ENV NODE_ENV="production"
ENV NEXT_TELEMETRY_DISABLED=1
EXPOSE 3000
CMD [ "/usr/local/bin/op", "run", "--env-file=/app/.env.production", "--", "node", "/app/server.js" ]