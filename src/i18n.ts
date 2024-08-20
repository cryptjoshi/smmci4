import { notFound } from "next/navigation";
import { getRequestConfig } from 'next-intl/server';

const locales = ['en', 'fr', 'nl','th'];

export default getRequestConfig(async ({ locale }:any) => {
  console.log(locale)
  if (!locales.includes(locale as any)) notFound();

  return {
    messages: (await import(`../messages/${locale}.json`)).default
  };
});