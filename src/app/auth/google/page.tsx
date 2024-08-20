'use client';

import { useState } from 'react';
import axios from 'axios';
import { Box, Button, Input, Text, VStack, Image } from '@chakra-ui/react';

interface GenerateResponse {
  secret: string;
  otpAuthUrl: string;
  qrcode: string;
}

interface VerifyResponse {
  verified: boolean;
}

interface IncreaseBalanceResponse {
  balance?: number;
  error?: string;
}

export default function Home() {
  const [qrcode, setQrcode] = useState<string>('');
  const [secret, setSecret] = useState<string>('');
  const [otp, setOtp] = useState<string>('');
  const [userid,setUserid] = useState(0);
  const [balance, setBalance] = useState<number>(1000);
  const [amount, setAmount] = useState<number>(0);
  const [message, setMessage] = useState<string>('');

  const generateQrCode = async () => {
    //const response = await axios.get<GenerateResponse>('/api/generate');
    const response = await fetch('https://authen.tsxbet.net/generate', { method: 'POST',
			  headers: {
				'Accept': 'application/json',
				'Content-Type': 'application/json',
				//'Authorization': 'Bearer ' +  token
			  },
        body: JSON.stringify({"userid":userid})
		  });
		  const res = await response.json();
   
        setQrcode(res.data.qrcode);
        setSecret(res.data.secret);
  };

  const verifyOtp = async () => {
    const response = await fetch('https://authen.tsxbet.net/verify', { method: 'POST',
    headers: {
    'Accept': 'application/json',
    'Content-Type': 'application/json',
    //'Authorization': 'Bearer ' +  token
    },
    body: JSON.stringify({ token: otp, secret })
  });
  const res = await response.json();
    //setQrcode(res.data.qrcode);
    //setSecret(res.data.secret);
    // const response = await axios.post<VerifyResponse>('/api/verify', { token: otp, secret });
   // console.log(res.data)
    if (res.data.verified) {
      
      setMessage('OTP verified successfully');
    } else {
      setMessage('Invalid OTP');
    }
  };

  const increaseBalance = async () => {
    const response = await fetch('https://authen.tsxbet.net/increase', { method: 'POST',
    headers: {
    'Accept': 'application/json',
    'Content-Type': 'application/json',
    //'Authorization': 'Bearer ' +  token
    },
    body: JSON.stringify({ token: otp, amount: parseInt(amount),userid:userid })
  })

  const res = await response.json();
    // const response = await axios.post<IncreaseBalanceResponse>('/api/increase-balance', { token: otp, amount: parseInt(amount) });
    if (response.status === 200) {
      setBalance(res.data.balance || balance);
      setMessage(`Balance increased. New balance: ${res.data.balance}`);
    } else {
      setMessage('Invalid OTP');
    }
  };

  return (
    <VStack spacing={4} p={8} align="center">
      <Text fontSize="2xl" fontWeight="bold">2FA with Google Authenticator</Text>
      <Input placeholder="Userid" value={userid} onChange={(e) => setUserid(e.target.value)} />
      <Button colorScheme="teal" onClick={generateQrCode}>Generate QR Code</Button>
      {qrcode && (
        <Box>
          <Image src={qrcode} alt="QR Code" />
          {/* <Text>Secret: {secret}</Text> */}
        </Box>
      )}
      <Input placeholder="Enter OTP" value={otp} onChange={(e) => setOtp(e.target.value)} />
      <Button colorScheme="teal" onClick={verifyOtp}>Verify OTP</Button>
      <Text fontSize="xl">Current Balance: {balance}</Text>
      <Input type="number" placeholder="Amount" value={amount} onChange={(e) => setAmount(parseInt(e.target.value))} />
      <Button colorScheme="teal" onClick={increaseBalance}>Increase Balance</Button>
      <Text color={message.includes("successfully")?"green.400":"red.400"} colorScheme={"yellow.300"}>{message}</Text>
    </VStack>
  );
}