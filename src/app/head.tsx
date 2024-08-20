import React from "react";
//import { useTranslations } from "next-intl";
export default function RootHead() {
	//const translated = useTranslations("Index");
	return <>
			<link rel="apple-touch-icon" href="/logo192.png" />
			<link rel="manifest" href="/manifest.json" />
			<link
				rel="shortcut icon"
				type="image/x-icon"
				href={process.env.NEXT_PUBLIC_BASE_PATH || '' + '/favicon.ico'}
			/>
 			<link href="https://fonts.googleapis.com/css2?family=Lobster&display=swap" rel="stylesheet" />
   
		  <title>PKD Dashboard</title>
      <meta name="viewport" content="width=device-width, initial-scale=1" />
      <meta name="theme-color" content="#000000" />
	</>
}