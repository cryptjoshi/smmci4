'use client';
// Chakra imports
import { Flex, Box, Text, useColorModeValue } from '@chakra-ui/react';
import CircularProgress from 'components/charts/CircularProgress';
import { VSeparator } from 'components/separator/Separator';

// Custom components
import Card from 'components/card/Card';
import { useEffect, useState } from 'react';
import { useRouter } from 'next/navigation';

export default function ProfitEstimation(props: { [x: string]: any }) {
  const { ...rest } = props;

  // Chakra Color Mode
  const textColor = useColorModeValue('secondaryGray.900', 'white');
  const cardColor = useColorModeValue('white', 'navy.700');
	const router = useRouter()
  const [users,setUsers] = useState<any>({})
   


  return (
    <Card
      p="20px"
      alignItems="center"
      flexDirection="column"
      w="100%"
      
    >
      <Text
        color={textColor}
        fontSize="lg"
        fontWeight="700"
        mb="10px"
        mx="auto"
      >
        ลูกค้าทั้งหมด
      </Text>
      <Text
        color="secondaryGray.600"
        fontSize="sm"
        fontWeight="500"
        maxW="200px"
        mx="auto"
        mb="10px"
      >
      </Text>
      <Flex
        justifyContent="center"
        alignItems="center"
        w="100%"
        px="10px"
        mb="8px"
      />
      <Box w="140px" mx="auto" mb="10px" mt="10px">
         <CircularProgress title="ลูกค้า Actived" percentage={ isNaN(parseFloat(((rest.data[0].sum/(rest.data[1].sum))*100).toFixed(2)))?0:parseFloat(((rest.data[0].sum/(rest.data[1].sum))*100).toFixed(2)) } />     
          
      </Box>
      <Card bg={cardColor} flexDirection="row" p="15px" px="20px" mt="auto">
        <Flex direction="column" py="5px">
          <Text
            fontSize="xs"
            color="secondaryGray.600"
            fontWeight="700"
            mb="5px"
          >
              { rest.data[0].name}
          </Text>
          <Text fontSize="lg" color={textColor} fontWeight="700">
              {rest.data[0].sum}  
          </Text>
        </Flex>
        <VSeparator ms="70px" me="20px" />
        <Flex direction="column" py="5px">
          <Text
            fontSize="xs"
            color="secondaryGray.600"
            fontWeight="700"
            mb="5px"
          >
           { rest.data[1].name}   
          </Text>
          <Text fontSize="lg" color={textColor} fontWeight="700">
        {rest.data[1].sum} 
          </Text>
        </Flex>
      </Card>
    </Card>
  );
}
