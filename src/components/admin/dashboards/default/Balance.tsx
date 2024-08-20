'use client'
// Chakra imports
import {
  Flex,
  Icon,
  Text,
  useColorModeValue,
  Menu,
  MenuButton,
  MenuItem,
  MenuList,
  useDisclosure,
} from '@chakra-ui/react';
import { Image } from 'components/image/Image';

// Custom components
import Transaction from 'components/dataDisplay/Transaction';
import Card from 'components/card/Card';

// Assets
import balanceImg from '/public/img/dashboards/balanceImg.png';
import fakeGraph from '/public/img/dashboards/fakeGraph.png';
import {
  MdOutlineMoreHoriz,
  MdDomain,
  MdElectricCar,
  MdSchool,
} from 'react-icons/md';
// Assets
import {
  MdOutlinePerson,
  MdOutlineCardTravel,
  MdOutlineLightbulb,
  MdOutlineSettings,
} from 'react-icons/md';
import { useEffect, useState } from 'react';
import { useRouter } from 'next/navigation';
import { getToken } from 'app/actions/userInfof';
export default function Balance(props: { [x: string]: any }) {
  const { ...rest } = props;
  // Ellipsis modals
  const {
    isOpen: isOpen1,
    onOpen: onOpen1,
    onClose: onClose1,
  } = useDisclosure();

  // Chakra Color Mode
  const blueIcon = useColorModeValue('blue.500', 'white');
  const greenIcon = useColorModeValue('green.500', 'white');
  const yellowIcon = useColorModeValue('yellow.500', 'white');
  const balanceBg = useColorModeValue('brand.900', '#1B254B');
  const textColor = useColorModeValue('secondaryGray.500', 'white');
  const textHover = useColorModeValue(
    { color: 'secondaryGray.900', bg: 'unset' },
    { color: 'secondaryGray.500', bg: 'unset' }
  );
  const bgList = useColorModeValue('white', 'whiteAlpha.100');
  const bgShadow = useColorModeValue(
    '14px 17px 40px 4px rgba(112, 144, 176, 0.08)',
    'unset'
  );

  
  const [sumTotal, setTotal] = useState<any>({});
  const router = useRouter()
  
  useEffect(() => {
      let isLoaded = false;

      const checkData = async () => {
          const token = getToken() //localStorage.getItem('token');
         
          if(!token){
            router.replace('/auth/sign-in')
        } else 
        {
          
             // console.log(new Date().format('yyyy-MM-dd'))
              const today = new Date().toJSON().slice(0, 10);
              //const raw = JSON.stringify({"startdate":today,"stopdate":today});
              let data;
           
          let res = await fetch('https://report.tsxbet.net/reports/sum/gateway', { method: 'POST',
            headers: {
              'Accept': 'application/json',
              'Content-Type': 'application/json',
              'Authorization': 'Bearer ' +  token
            },
       // body: raw
        });
        data = await res.json();
      
        if(data.status){
            //console.log(data.data)
            //if(!isLoaded){
             setTotal(data.data)
            // const sumtotal = data.data.data.reduce((accumulator:any, current:any) => accumulator + current)
       
            // setTotal(data.data[0].sum.toFixed(2).toString())
            // lineChartOptionsOverallRevenue.xaxis.categories = data.data.dayArray;
           //  lineChartDataOverallRevenue.xaxis.categories = data.data.daysArray;
          //}
           // setAuthenticated(true)
        } else {
         //router.replace('/auth/sign-in')
        } 
            
          }
      }

       const timeout = setTimeout(() => {
           //setMounted(true);
           isLoaded = true;
      }, 3000);
      checkData()
      return () => {
          //isLoaded = true;
      //	checkData();
           clearTimeout(timeout);
       };


      
  }, []);


  return (
      <Card flexDirection="column" w="100%" {...rest}>
          <Flex
              justify="space-between"
              p="20px"
              mb="20px"
              borderRadius="16px"
              bgColor={balanceBg}
              bgImage={balanceImg?.src}
              bgPosition="right"
              bgSize="cover"
          >
              <Flex align="center" justify="space-between" w="100%">
                  <Flex flexDirection="column" me="20px">
                      <Text color="white" fontSize="sm" fontWeight="500">
                          ยอดเงินคงเหลือ
                      </Text>
                      <Text
                          color="white"
                          fontSize="34px"
                          fontWeight="700"
                          lineHeight="100%"
                      >
                          {parseFloat(sumTotal.total).toFixed(2)}
                      </Text>
                  </Flex>
                  <Flex
                      flexDirection="column"
                      ms="auto"
                      justify="space-between"
                      align="flex-end"
                  >
                      <Menu isOpen={isOpen1} onClose={onClose1}>
                          <MenuButton onClick={onOpen1}>
                              <Icon
                                  cursor="pointer"
                                  as={MdOutlineMoreHoriz}
                                  color="white"
                                  mt="-2px"
                                  mb="12px"
                                  w="30px"
                                  h="30px"
                              />
                          </MenuButton>
                          <MenuList
                              w="150px"
                              minW="unset"
                              maxW="150px !important"
                              border="transparent"
                              backdropFilter="blur(63px)"
                              bg={bgList}
                              boxShadow={bgShadow}
                              borderRadius="20px"
                              p="15px"
                          >
                              <MenuItem
                                  transition="0.2s linear"
                                  color={textColor}
                                  _hover={textHover}
                                  p="0px"
                                  borderRadius="8px"
                                  _active={{
                                      bg: 'transparent',
                                  }}
                                  _focus={{
                                      bg: 'transparent',
                                  }}
                                  mb="10px"
                              >
                                  <Flex align="center">
                                      <Icon
                                          as={MdOutlinePerson}
                                          h="16px"
                                          w="16px"
                                          me="8px"
                                      />
                                      <Text fontSize="sm" fontWeight="400">
                                          Panel 1
                                      </Text>
                                  </Flex>
                              </MenuItem>
                              <MenuItem
                                  transition="0.2s linear"
                                  p="0px"
                                  borderRadius="8px"
                                  color={textColor}
                                  _hover={textHover}
                                  _active={{
                                      bg: 'transparent',
                                  }}
                                  _focus={{
                                      bg: 'transparent',
                                  }}
                                  mb="10px"
                              >
                                  <Flex align="center">
                                      <Icon
                                          as={MdOutlineCardTravel}
                                          h="16px"
                                          w="16px"
                                          me="8px"
                                      />
                                      <Text fontSize="sm" fontWeight="400">
                                          Panel 2
                                      </Text>
                                  </Flex>
                              </MenuItem>
                              <MenuItem
                                  transition="0.2s linear"
                                  p="0px"
                                  borderRadius="8px"
                                  color={textColor}
                                  _hover={textHover}
                                  _active={{
                                      bg: 'transparent',
                                  }}
                                  _focus={{
                                      bg: 'transparent',
                                  }}
                                  mb="10px"
                              >
                                  <Flex align="center">
                                      <Icon
                                          as={MdOutlineLightbulb}
                                          h="16px"
                                          w="16px"
                                          me="8px"
                                      />
                                      <Text fontSize="sm" fontWeight="400">
                                          Panel 3
                                      </Text>
                                  </Flex>
                              </MenuItem>
                              <MenuItem
                                  transition="0.2s linear"
                                  color={textColor}
                                  _hover={textHover}
                                  p="0px"
                                  borderRadius="8px"
                                  _active={{
                                      bg: 'transparent',
                                  }}
                                  _focus={{
                                      bg: 'transparent',
                                  }}
                              >
                                  <Flex align="center">
                                      <Icon
                                          as={MdOutlineSettings}
                                          h="16px"
                                          w="16px"
                                          me="8px"
                                      />
                                      <Text fontSize="sm" fontWeight="400">
                                          Panel 4
                                      </Text>
                                  </Flex>
                              </MenuItem>
                          </MenuList>{' '}
                      </Menu>
                      <Image src={fakeGraph} w="59px" h="17px" alt="" />
                  </Flex>
              </Flex>
          </Flex>
          <Text
              color="secondaryGray.600"
              fontWeight="500"
              fontSize="sm"
              mb="10px"
          >
            {"ช่องทาง"}
          </Text>
          <Flex direction="column">
            {
                sumTotal.gateway && sumTotal.gateway.map((item:any,index:any)=>(
                    <Transaction
                    key={index}
                    mb="20px"
                    name={item.gateway_name}
                    date="Today, 16:36"
                    sum={item.balance}
                    icon={
                        <Icon as={MdDomain} color={blueIcon} w="20px" h="18px" />
                    }
                />
                ))
            }
             {/* { sumTotal.data.gateway.map((item:any,index:any)=> (
                 <Transaction
                 key={index}
                 mb="20px"
                 name={item.gateway_name}
                 date="Today, 16:36"
                 sum={item.balance}
                 icon={
                     <Icon as={MdDomain} color={blueIcon} w="20px" h="18px" />
                 }
             />)
                )} */}
              {/* <Transaction
                  mb="20px"
                  name="Bill & Taxes"
                  date="Today, 16:36"
                  sum="-$154.50"
                  icon={
                      <Icon as={MdDomain} color={blueIcon} w="20px" h="18px" />
                  }
              />
              <Transaction
                  mb="20px"
                  name="Car Energy"
                  date="23 Jun, 13:06"
                  sum="-$40.50"
                  icon={
                      <Icon
                          as={MdElectricCar}
                          color={greenIcon}
                          w="20px"
                          h="18px"
                      />
                  }
              />
              <Transaction
                  name="Design Course"
                  date="21 Jun, 19:04"
                  sum="-$70.00"
                  icon={
                      <Icon
                          as={MdSchool}
                          color={yellowIcon}
                          w="20px"
                          h="18px"
                      />
                  }
              /> */}
          </Flex>
      </Card>
  );
}
