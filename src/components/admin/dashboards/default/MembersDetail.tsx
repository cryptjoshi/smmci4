/* eslint-disable */

import {
    Badge,
    Box,
    Flex,
    Grid,
    Button,
    Icon,
    Link,
    Table,
    Tbody,
    Td,
    Th,
    Thead,
    Tr,
    useColorModeValue,
    HStack,
    Stack,
    Heading,
    AbsoluteCenter,
    Divider,
    FormControl,
    FormLabel,
    Input,
    InputGroup,
    InputRightElement,
    Text
  } from '@chakra-ui/react';
  import Swal from 'sweetalert2'
  import { HSeparator } from 'components/separator/Separator';
  import { useForm, SubmitHandler } from "react-hook-form"
  import DefaultAuthLayout from 'layouts/auth/Default';
  import {
    PaginationState,
    createColumnHelper,
    useReactTable,
    ColumnFiltersState,
    getCoreRowModel,
    getFilteredRowModel,
    getFacetedRowModel,
    getFacetedUniqueValues,
    getFacetedMinMaxValues,
    getPaginationRowModel,
    getSortedRowModel,
    SortingState,
    flexRender,
  } from '@tanstack/react-table';

  import withReactContent from 'sweetalert2-react-content';

  import { MdOutlineCalendarToday,MdChevronLeft,MdChevronRight } from 'react-icons/md';
  import useSWR from "swr";
  // Assets
  import { SearchBar } from 'components/navbar/searchBar/SearchBar';
  import { MdOutlineRemoveRedEye } from 'react-icons/md';
  import { RiEyeCloseLine } from 'react-icons/ri';
  // Custom components
  import Card from 'components/card/Card';
  import * as React from 'react';
  import { useRouter,useParams } from 'next/navigation';
  import { useEffect, useState } from 'react';
  import Transfer from '../../../dataDisplay/Transfer';
  import { convertDate } from 'utils';
  import { ViewIcon, ViewOffIcon } from '@chakra-ui/icons'
  import Banner from 'views/admin/profile/components/Banner';
  import SwitchField from 'components/fields/SwitchField';
  // Assets
import banner from 'img/auth/banner.png';
import avatar from 'img/avatars/avatar4.png';
import { getToken } from 'app/actions/userInfof';
import { updateUsers,depositAmount,withdrawAmount } from 'app/actions/auth';
  // Assets
  type Authens = {
    username: string
    fullname: string
    banknumber:string
    bankname:string
    password: string
    balance:number;
    provider_password:string
  }
  
  type RowObj = {
    createdAt:string;
    MemberID:string;
    MemberName:string;
    GameID:string;
    GameRoundID:string;
    BeforeBalance:string;
    BetAmount:number;
    PayoutAmount:number;
    PayoutDetail:string;
    AfterBalance:number;
    win:number;
    winloss:number;
    loss:number;
    turnover:number;
    month:number;
  };
  //const startdate =  new Date(new Date().setDate(new Date().getDate() - 7)).toJSON().slice(0, 10);
   // const stopdate =  new Date(new Date().setDate(new Date().getDate() + 7)).toJSON().slice(0, 10);
    //const token = localStorage.getItem('token');
    //const raw = JSON.stringify({"startdate":startdate,"stopdate":stopdate,"prefix":"all","statement_type":"all","status":"all"});
      
  const columnHelper = createColumnHelper<RowObj>();
  const fetcher = (url:string) => fetch(url,{ method: 'POST',
    headers: {
    'Accept': 'application/json',
    'Content-Type': 'application/json',
    'Authorization': 'Bearer ' +  getToken()// localStorage.getItem('token')
    },
   body: JSON.stringify({"startdate":new Date(new Date().setDate(new Date().getDate() - 7)).toJSON().slice(0, 10),"stopdate":new Date(new Date().setDate(new Date().getDate() + 7)).toJSON().slice(0, 10),"prefix":"all","statement_type":"all","status":"all"})
  }).then((res) => res.json());
  // const columns = columnsDataCheck;

  const MySwal = withReactContent(Swal);

  export default function MembersDetail(props:any) {
    // const { tableData } = props;
    //const params = useParams()
    const params = useParams();
    const {id} = params
 
  
    const [sorting, setSorting] = useState<SortingState>([]);
    const textColor = useColorModeValue('secondaryGray.900', 'white');
    const textColorSecondary = useColorModeValue('secondaryGray.600', 'white');
    const textColorPrimary = useColorModeValue('secondaryGray.900', 'white');
    const borderColor = useColorModeValue('gray.200', 'whiteAlpha.100');
    const boxBg = useColorModeValue('secondaryGray.300', 'whiteAlpha.100');
    const textColorBrand = useColorModeValue('brand.500', 'white');
    const brandStars = useColorModeValue('brand.500', 'brand.400');
    const brandColor = useColorModeValue('brand.500', 'brand.400');
    const googleBg = useColorModeValue('secondaryGray.300', 'whiteAlpha.200');
    const googleText = useColorModeValue('navy.700', 'white');
    const googleHover = useColorModeValue(
      { bg: 'gray.200' },
      { bg: 'whiteAlpha.300' },
    );
    const googleActive = useColorModeValue(
      { bg: 'secondaryGray.300' },
      { bg: 'whiteAlpha.200' },
    );
    const router = useRouter()
    //const [transfers,setTransfers] = useState<RowObj[]>([])
     
    //const [data, setData] =  useState(() => [...transfers]);
    const [errorx, setError] = useState<string | null>(null);
    const [show, setShow] = React.useState(false);
    const [showb, setShowb] = React.useState(false);
    const [showc, setShowc] = React.useState(false);
    const handleClick = () => setShow(!show);
    const {
      register,
      handleSubmit,
      watch,
      formState: { errors },
    } = useForm<Authens>()

    const {
      register: register2,
      formState: { errors: errors2 },
      handleSubmit: handleSubmit2,
    } = useForm({
      mode: "onBlur",
    });

    const {
      register: register3,
      formState: { errors: errors3 },
      handleSubmit: handleSubmit3,
    } = useForm({
      mode: "onBlur",
    });



    const [columnFilters, setColumnFilters] = useState<ColumnFiltersState>([]);
   
    const [globalFilter, setGlobalFilter] =  useState('');
    
    const { data, error, isLoading } = useSWR<Authens[]>(id ? `https://report.tsxbet.net/reports/member/${id}` : null,
    fetcher
    );
  
    const onSubmit: SubmitHandler<Authens> = (formdata:any) =>{ 
  
      formdata.userid = data.data[0].id
      formdata.username = data.data[0].username
      Object.assign(formdata,{id:data.data[0].id})
      
      if( formdata.newpassword!="" && formdata.repassword!="" && formdata.newpassword != formdata.repassword){
        setError("รหัสผ่านไม่ตรงกัน กรุณาตรวจสอบ!")
        setTimeout(() => {
          setError(null);
        }, 3000); // ล้างข้อความ error หลังจาก 3 วินาที
       
      }else {
          
       
          updateUsers(formdata).then((result:any)=>{
            
            if(result.result.status==200){
              MySwal.fire({
                title: 'แจ้งเตือน',
                text: 'ปรับปรุงยูสเซอร์ สำเร็จ!!.',
                icon: 'success',
                confirmButtonText: 'ตกลง',
                
              }) 
              // setError("ปรับปรุงสมาชิก สำเร็จ!")
              setTimeout(() => {
                //setError(null);
                router.replace('/admin/members')
              }, 1000);
              
            } else {
              // setError("ปรับปรุงไม่สำเร็จ สมาชิก ซ้ำ!")
              // setTimeout(() => {
              //   setError(null);
              // }, 3000); // ล้างข้อความ error หลังจาก 3 วินาที
              MySwal.fire({
                title: 'แจ้งเตือน',
                text: 'ปรับปรุงไม่สำเร็จ สมาชิก ซ้ำ!',
                icon: 'error',
                confirmButtonText: 'ตกลง',
                
              });
            }
          })
        }
       
    }
   
    const onDeposit: SubmitHandler<any> = (formdata:any) =>{
       
      const raw = {"id":data.data[0].id,"username":data.data[0].username,"amount":formdata.amount}
      
      depositAmount(raw).then((response:any)=>{
        console.log(response)
      })
    }
    const onWithdraw: SubmitHandler<any> = (formdata:any) =>{
       
      const raw = {"id":data.data[0].id,"username":data.data[0].username,"amount":formdata.amount}
 
      withdrawAmount(raw).then((response:any)=>{
        console.log(response)
      })
    }
    if (error) return <>"An error has occurred."</>;
    if (isLoading) return <>"Loading..."</>;
 
    return (
     
     <>
      
       
       <Flex
      minH={'100vh'}
      align={'center'}
      justify={'center'}
      bg={useColorModeValue('gray.50', 'gray.800')}>
      <Stack spacing={8} mx={'auto'} maxW={'lg'} py={12} px={6}> 
      <form onSubmit={handleSubmit(onSubmit)}> 
        <Box
          rounded={'lg'}
          bg={useColorModeValue('white', 'gray.700')}
          boxShadow={'lg'}
          p={8}>
              
          <Stack spacing={4}>
            <HStack>
            <Banner
          gridArea="1 / 1 / 2 / 2"
          banner={banner}
          avatar={avatar}
          name={data.data[0].fullname}
          balance={data.data[0].balance?data.data[0].balance.toLocaleString():0}
          turnover={data.data[0].turnover?data.data[0].turnover.toLocaleString():0}
          betamount={data.data[0].betamount?data.data[0].betamount.toLocaleString():"0.00"}
          payout={data.data[0].win?data.data[0].win.toLocaleString():data.data[0].loss.toLocaleString()}
          
        />
            </HStack>
            <HStack>
              <Box>
                <FormControl id="fullname" isRequired>
                  <FormLabel>ชื่อ นามสกุล</FormLabel>
                  <Input type="text" defaultValue={data.data[0].fullname}  {...register("fullname", { disabled: true })} />
                </FormControl>
              </Box>
              
            </HStack>
            <FormControl id="username" isRequired>
              <FormLabel>ชื่อผู้ใช้งาน</FormLabel>
              <Input type="text" defaultValue={data.data[0].username}  {...register("username", { disabled: true })}/>
            </FormControl>
            <FormControl id="bankname" isRequired>
                  <FormLabel>ธนาคาร</FormLabel>
                  <Input type="text" defaultValue={data.data[0].bankname}  {...register("bankname", { disabled: true })} />
            </FormControl>
            <FormControl id="banknumber" isRequired>
                  <FormLabel>หมายเลขบัญชี</FormLabel>
                  <Input type="text" defaultValue={data.data[0].banknumber}  {...register("banknumber", { disabled: true })} />
            </FormControl>
            <FormControl id="password" isRequired>
              <FormLabel>รหัสผ่าน</FormLabel>
              <InputGroup>
                <Input type={show ? 'text' : 'password'} defaultValue={data.data[0].password.slice(0,6)}  {...register("password", { required: true })} />
                <InputRightElement h={'full'}>
                  <Button
                    variant={'ghost'}
                    onClick={() => setShow((showpass:any) => !show)}>
                    {show ? <ViewIcon /> : <ViewOffIcon />}
                  </Button>
                </InputRightElement>
              </InputGroup>
            </FormControl>
            <FormControl id="newpassword" isRequired>
              <FormLabel>รหัสผ่านใหม่</FormLabel>
              <InputGroup>
                <Input type={showb ? 'text' : 'password'} {...register("newpassword")}   />
                <InputRightElement h={'full'}>
                  <Button
                    variant={'ghost'}
                    onClick={() => setShowb((showpass:any) => !showb)}>
                    {showb ? <ViewIcon /> : <ViewOffIcon />}
                  </Button>
                </InputRightElement>
              </InputGroup>
            </FormControl>
            <FormControl id="repassword" isRequired>
              <FormLabel>รหัสผ่านใหม่อีกครั้ง</FormLabel>
              <InputGroup>
                <Input type={showc ? 'text' : 'password'}   {...register("repassword")}    />
                <InputRightElement h={'full'}>
                  <Button
                    variant={'ghost'}
                    onClick={() => setShowc((showpass:any) => !showc)}>
                    {showc ? <ViewIcon /> : <ViewOffIcon />}
                  </Button>
                </InputRightElement>
              </InputGroup>
            </FormControl>
            <FormControl id="balance" isRequired>
              <FormLabel>เงินคงเหลือ</FormLabel>
              <Input type="text"   defaultValue={data.data[0].balance.toFixed(2)}  {...register("balance", { disabled: true })}/>
            </FormControl>
            <Stack spacing={10} pt={2}>
            <SwitchField reversed={true} isChecked={!data.data[0].status} colorScheme="brand" {...register("status")} color={textColorPrimary} fontSize='sm' mb='20px' id='2' label='สถานะ' />
              <Button
              type="submit"
                loadingText="กำลังบันทึก"
                size="lg"
                bg={'green.400'}
                color={'white'}
                _hover={{
                  bg: 'green.500',
                }}>
               เปลี่ยนรหัสผ่าน
              </Button>
            </Stack>
          
                {errorx && (
               <Box
               mt={4}
               p={3}
               bg="red.500"
               color="white"
               borderRadius="md"
               borderLeft="4px solid"
               borderColor="white.400" // ขอบด้านซ้ายเป็นสีเขียว
               transition="all .5s ease"
             >
                {errorx}
              </Box>
            )}
          </Stack>
     
        
        </Box>
        </form>
        <form onSubmit={handleSubmit2(onDeposit)}> 
        <Box
          rounded={'lg'}
          bg={useColorModeValue('white', 'gray.700')}
          boxShadow={'lg'}
          p={8}>
        <Stack pt={5}>
         <HSeparator />
            {/* <Text color="gray.400" mx="14px">
              or
            </Text>
            <HSeparator /> */}
        </Stack>
        <Stack spacing={10} pt={2}>
        
        <HStack>
        <FormControl id="amount" isRequired pb={5}>
            <FormLabel>ยอดงิน</FormLabel>
            <Input type="text" {...register2("amount", { disabled: false })}/>
          </FormControl>
        </HStack>
        </Stack>
        <HStack w={"100%"}>
              <Button
                    loadingText="ทดสอบ"
                    size="lg"
                    bg={'green.400'}
                    color={'white'}
                    w={"100%"}
                    _hover={{
                      bg: 'green.500',
                    }}
                    type="submit"
                    >
                  ทดสอบฝากเงิน
                  </Button>
        </HStack>
        </Box>
        </form>
        <form onSubmit={handleSubmit3(onWithdraw)}> 
        <Box
          rounded={'lg'}
          bg={useColorModeValue('white', 'gray.700')}
          boxShadow={'lg'}
          p={8}>
        <Stack pt={5}>
         <HSeparator />
            {/* <Text color="gray.400" mx="14px">
              or
            </Text>
            <HSeparator /> */}
        </Stack>
        <Stack spacing={10} pt={2}>
        
        <HStack>
        <FormControl id="amount" isRequired pb={5}>
            <FormLabel>ยอดเงิน</FormLabel>
            <Input type="text" {...register3("amount", { disabled: false })}/>
          </FormControl>
        </HStack>
        </Stack>
        <HStack w={"100%"}>
              <Button
                    loadingText="ทดสอบ"
                    size="lg"
                    bg={'green.400'}
                    color={'white'}
                    w={"100%"}
                    _hover={{
                      bg: 'green.500',
                    }}
                    type="submit"
                    >
                  ทดสอบถอนเงิน
                  </Button>
        </HStack>
        </Box>
        </form>
      </Stack>
    </Flex>
    </>
    );
  }
  
  