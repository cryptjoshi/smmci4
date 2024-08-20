'use client'
// Chakra imports
import { Button, Icon, Text, useColorModeValue } from '@chakra-ui/react';

// Custom components
import Card from 'components/card/Card';
import Transfer from 'components/dataDisplay/Transfer';
// Assets
import avatar1 from '/public/img/avatars/avatar1.png';
import avatar2 from '/public/img/avatars/avatar2.png';
import avatar3 from '/public/img/avatars/avatar3.png';
import avatar4 from '/public/img/avatars/avatar4.png';
import { BsArrowRight } from 'react-icons/bs';
import { useEffect, useState } from 'react';
import { useRouter } from 'next/navigation';
import { setTextRange } from 'typescript';
import { getToken } from 'app/actions/userInfof';

export default function YourTransfers(props: { [x: string]: any }) {
  const { ...rest } = props;

  // Chakra Color Mode
  const textColor = useColorModeValue('secondaryGray.900', 'white');
  const brandColor = useColorModeValue('brand.500', 'white');
  const router = useRouter()
  const [transfers,setTransfers] = useState<any>({})
  const [loading, setLoading] = useState(true);
	useEffect(() => {
		

		const checkData = async () => {
			const token =  getToken() //localStorage.getItem('token');
		   
			if(!token){
			  router.replace('/auth/sign-in')
		  } 
      else  {
			
			   // console.log(new Date().format('yyyy-MM-dd'))
				const today = new Date().toJSON().slice(0, 10);
				//const raw = JSON.stringify({"startdate":today,"stopdate":today});
				let data;
			 
			let res = await fetch('https://report.tsxbet.net/reports/sum/dailytransfer', { method: 'POST',
			  headers: {
				'Accept': 'application/json',
				'Content-Type': 'application/json',
				'Authorization': 'Bearer ' +  token
			  },
		 // body: raw
		  });
		  data = await res.json();
   
		  if(data.status){
			 
			//if(!isLoaded){
			   setTransfers(data.data)
         setLoading(false)
			  // const sumtotal = data.data.data.reduce((accumulator:any, current:any) => accumulator + current)
		 
			  // setTotal(data.data[0].sum.toFixed(2).toString())
        // lineChartOptionsOverallRevenue.xaxis.categories = data.data.dayArray;
			 //  lineChartDataOverallRevenue.xaxis.categories = data.data.daysArray;
			//}
			 // setAuthenticated(true)
		  } else {
		//	router.replace('/auth/sign-in')
		  } 
			  
			}
		}

		 const timeout = setTimeout(() => {
		 	//setMounted(true);
		 //	isLoaded = true;
		}, 3000);

 
		checkData()
  
		return () => {
			//isLoaded = true;
		//	checkData();
		 	clearTimeout(timeout);
		 };


		
	}, []);
  if (loading) {
    return <p>Loading...</p>; // ข้อความระหว่างรอข้อมูล
}

if (!transfers) {
    return <p>No data available</p>; // ข้อความเมื่อไม่มีข้อมูล
}

  return (
    <Card
      justifyContent="center"
      flexDirection="column"
      w="100%"
      mb={{ base: '20px', lg: '0px' }}
      pb="20px"
      {...rest}
    >
      <Text
        color={textColor}
        fontSize="lg"
        fontWeight="700"
        lineHeight="100%"
        mb="26px"
      >
        {"ยอดเคลื่อนไหว ประจำวัน"}
      </Text>
      { 
         
        transfers && transfers.map((item: any,index:any) =>(
          <Transfer
          key={index}
          mb="20px"
          name={item.label}
          date="Today, 16:36"
          sum={item.amount}
          avatar={avatar1}
      />
        ))
     
      
      }
     {/*} <Transfer
        mb="20px"
        name="From Alex Manda"
        date="Today, 16:36"
        sum="+$50"
        avatar={avatar1}
      />
        <Transfer
        mb="20px"
        name="To Laura Santos"
        date="Today, 08:49"
        sum="-$27"
        avatar={avatar2}
      />
      <Transfer
        mb="20px"
        name="From Jadon S."
        date="Yesterday, 14:36"
        sum="+$157"
        avatar={avatar3}
      />
      <Transfer
        mb="20px"
        name="From Esthera J."
        date="Yesterday, 09:42"
        sum="+$92"
        avatar={avatar4}
      />

      <Button
        p="0px"
        ms="auto"
        variant="no-hover"
        bg="transparent"
        my={{ sm: '1.5rem', lg: '0px' }}
      >
        <Text
          fontSize="sm"
          color={brandColor}
          fontWeight="bold"
          cursor="pointer"
          transition="all .3s ease"
          my={{ sm: '1.5rem', lg: '0px' }}
          _hover={{ me: '4px' }}
        >
          View all
        </Text>
        <Icon
          as={BsArrowRight}
          w="18px"
          h="18px"
          color={brandColor}
          transition="all .3s ease"
          ms=".3rem"
          cursor="pointer"
          _hover={{ transform: 'translate(4px)' }}
        />
      </Button> */}
    </Card>
  );
}
