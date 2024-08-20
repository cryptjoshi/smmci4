/** @type {import('next').NextConfig} */
// const withNextIntl = require('next-intl/plugin')(
//   './src/i18n.ts'
// );
// const nextConfig = withNextIntl({
//   swcMinify: true,
//   basePath: process.env.NEXT_PUBLIC_BASE_PATH,
//   assetPrefix: process.env.NEXT_PUBLIC_BASE_PATH,
//   images: {
//     domains: [
//       'images.unsplash.com',
//       'i.ibb.co',
//       'scontent.fotp8-1.fna.fbcdn.net',
//     ],
//     // Make ENV
//     unoptimized: true,
//   },
  
// });

const nextConfig = {
  swcMinify: true,
  basePath: process.env.NEXT_PUBLIC_BASE_PATH,
  assetPrefix: process.env.NEXT_PUBLIC_BASE_PATH,
  images: {
    domains: [
      'images.unsplash.com',
      'i.ibb.co',
      'scontent.fotp8-1.fna.fbcdn.net',
    ],
    // Make ENV
    unoptimized: true,
  },
  
};

// module.exports = withTM(nextConfig);
module.exports = nextConfig;
