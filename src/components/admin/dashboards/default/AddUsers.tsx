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
    Text,
    Select
 
  } from '@chakra-ui/react';
  
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
import { getPrefixs, insertAdmin } from 'app/actions/auth';
import Swal from 'sweetalert2'
  // Assets
  type Authens = {
    username: string
    fullname: string
    banknumber:string
    bankname:string
    password: string
    repassword:string;
    balance:number;
    prefix:string;
    role:string;
    provider_password:string
    status:string;
  }
  
  type RowObj = {
    id:string;
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


  export default function AddUsers(props:any) {
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

    const [error, setError] = useState<string | null>(null);

     
    
    const [show, setShow] = React.useState(false);
    const [showx, setShowx] = React.useState(false);
    const [status,setStatus] = React.useState(false);
    //const handleClick = () => setShow(!show);
    const {
      register,
      handleSubmit,
      watch,
      formState: { errors },
    } = useForm<Authens>()

    type Option = {
      value: string;
      label: string;
    };
   
    // const handleChange = () => {
    //   setStatus(!!status)
    //   // do the rest here
    // } 
    const handleChange = (e:any) => {
      setStatus(!!status);
    };

    const onSubmit: SubmitHandler<Authens> = (data:any) =>{ 
  
     // fetchData(data.username,data.password)
     
     if(data.password != data.repassword){
      setError("รหัสผ่านไม่ตรงกัน กรุณาตรวจสอบ!")
      setTimeout(() => {
        setError(null);
      }, 3000); // ล้างข้อความ error หลังจาก 3 วินาที
     
    }else {
        data.status = status
        
        insertAdmin(data).then((result:any)=>{
          //console.log(result)
          if(result.result.status==200){
            // setError("เพิ่มยูสเซอร์ สำเร็จ!")
            // setTimeout(() => {
            //   setError(null);
            //   router.replace('/admin/users')
            // }, 3000);
            MySwal.fire({
              title: 'แจ้งเตือน',
              text: 'เพิ่มผู้ดูแล สำเร็จ!!.',
              icon: 'success',
              confirmButtonText: 'ตกลง',
              
            }) 
            
          } else {
            // setError("เพิ่มไม่สำเร็จ ยูสเซอร์ ซ้ำ!")
            // setTimeout(() => {
            //   setError(null);
            // }, 3000); // ล้างข้อความ error หลังจาก 3 วินาที
            MySwal.fire({
              title: 'แจ้งเตือน',
              text: 'เพิ่มผู้ดูแล ไม่สำเร็จ!!.',
              icon: 'error',
              confirmButtonText: 'ตกลง',
              
            }) 
          }
        })
      }
    }
    
    
    const [columnFilters, setColumnFilters] = useState<ColumnFiltersState>([]);
   
    const [globalFilter, setGlobalFilter] =  useState('');

    const [prefixs,setPrefixs] = useState([])
    
  //   const { data, error, isLoading } = useSWR(id ? `https://report.tsxbet.net/reports/users/${id}` : null,
  //   fetcher
  // );

  //const [user,setUser] = useState<Authens>(()=>[...data])
 
    // if (error) return <>"An error has occurred."</>;
    // if (isLoading) return <>"Loading..."</>;
    // if(!isLoading){
    //  // set(data)
    // }
    //console.log(data.data[0])

    useEffect(()=>{
      const getprefixs = async ()=>{
        const prefixx = await getPrefixs()
        console.log(prefixx.data)
        setPrefixs(prefixx.data)
      }
      getprefixs()
    },[])

    return (
      <>
     
      <form onSubmit={handleSubmit(onSubmit)}> 
       
       <Flex
      minH={'100vh'}
      align={'center'}
      justify={'center'}
      bg={useColorModeValue('gray.50', 'gray.800')}>
      <Stack spacing={8} mx={'auto'} maxW={'lg'} py={12} px={6}>
        {/* <Stack align={'center'}>
          <Heading fontSize={'4xl'} textAlign={'center'}>
            Sign up
          </Heading>
          <Text fontSize={'lg'} color={'gray.600'}>
            to enjoy all of our cool features ✌️
          </Text>
        </Stack> */}
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
          name={""}
          balance={0}
          turnover={0}
          betamount={"0.00"}
          payout={"0.00"}
          role={""}
          
        />
            </HStack>
            <HStack>
              <Box>
                <FormControl id="fullname" isRequired>
                  <FormLabel>คำนำหน้า</FormLabel>
                  <Input type="text"  {...register("prefix", { disabled: false })} />
                  {/* <Select
                                      fontSize="sm"
                                      id="color"
                                      variant="main"
                                      h="44px"
                                      maxH="44px"
                                      fontWeight="400"
                                      me="20px"
                                      defaultValue="dark_grey"
                                      {...register("role", { disabled: false })}
                                  >
                                 { 
                                   prefixs.forEach((element:any) => {
                                    <option value={element.name}>{element.name}</option>
                                  }) 
                          
                                
                              
                                  
                                 }   
                                    
                                      
                                  </Select> */}
                </FormControl>
              </Box>
              {/* <Box>
                <FormControl id="lastName">
                  <FormLabel>Last Name</FormLabel>
                  <Input type="text" />
                </FormControl>
              </Box> */}
            </HStack>
            <FormControl id="username" isRequired>
              <FormLabel>ชื่อผู้ใช้งาน</FormLabel>
              <Input type="text"   {...register("username", { disabled: false })}/>
            </FormControl>
            <FormControl id="role" isRequired>
           
                                  <FormLabel
                                      ms="10px"
                                      htmlFor="color"
                                      fontSize="sm"
                                      color={textColor}
                                      fontWeight="bold"
                                      _hover={{ cursor: 'pointer' }}
                                  >
                                     สิทธิ์ใช้งาน
                                  </FormLabel>
                                  <Select
                                      fontSize="sm"
                                      id="color"
                                      variant="main"
                                      h="44px"
                                      maxH="44px"
                                      fontWeight="400"
                                      me="20px"
                                      defaultValue="dark_grey"
                                      {...register("role", { disabled: false })}
                                  >
                                      <option value="user">
                                          User
                                      </option>
                                      <option value="sa">SA</option>
                                      
                                  </Select>
                          
                   
                  {/* <Input type="text"   {...register("role", { disabled: false })} /> */}
                </FormControl>
            <FormControl id="password" isRequired>
              <FormLabel>รหัสผ่าน</FormLabel>
              <InputGroup>
                <Input type={show ? 'text' : 'password'}  {...register("password", { required: true })} />
                <InputRightElement h={'full'}>
                  <Button
                    variant={'ghost'}
                    onClick={() => setShow((showpass:any) => !show)}>
                    {show ? <ViewIcon /> : <ViewOffIcon />}
                  </Button>
                </InputRightElement>
              </InputGroup>
            </FormControl>
            {/* <FormControl id="newpassword" isRequired>
              <FormLabel>รหัสผ่านใหม่</FormLabel>
              <InputGroup>
                <Input type={show ? 'text' : 'password'}  />
                <InputRightElement h={'full'}>
                  <Button
                    variant={'ghost'}
                    onClick={() => setShow((showpass:any) => !show)}>
                    {show ? <ViewIcon /> : <ViewOffIcon />}
                  </Button>
                </InputRightElement>
              </InputGroup>
            </FormControl> */}
            <FormControl id="reassword" isRequired>
              <FormLabel>รหัสผ่านใหม่อีกครั้ง</FormLabel>
              <InputGroup>
                <Input id="repassword" type={showx ? 'text' : 'password'} {...register("repassword", { required: true })}  />
                <InputRightElement h={'full'}>
                  <Button
                    variant={'ghost'}
                    onClick={() => setShowx((showpass:any) => !showx)}>
                    {showx ? <ViewIcon /> : <ViewOffIcon />}
                  </Button>
                </InputRightElement>
              </InputGroup>
            </FormControl>
            {/* <FormControl id="balance" isRequired>
              <FormLabel>เงินคงเหลือ</FormLabel>
              <Input type="text"   defaultValue={data.data[0].balance.toFixed(2)}  {...register("balance", { disabled: true })}/>
            </FormControl> */}
            <Stack spacing={10} pt={2}>
            <SwitchField reversed={true}   onChange={handleChange} {...register("status")}   color={textColorPrimary} fontSize='sm' mb='20px' id='2' label={'ไม่ทำงาน'} />
              <Button
                loadingText="กำลังบันทึก"
                size="lg"
                bg={'green.400'}
                color={'white'}
                type="submit"
                _hover={{
                  bg: 'green.500',
                }}>
               บันทึก
              </Button>
            </Stack>
            <Stack spacing={10} pt={2}>
              <Button
                loadingText="ยกเลิก"
                size="lg"
                bg={'orange.400'}
                color={'white'}
                _hover={{
                  bg: 'orange.500',
                }}>
                ยกเลิก
              </Button>
           {/* แสดง error message ถ้ามี */}
            {error && (
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
                {error}
              </Box>
            )}
            </Stack>
            
          </Stack>
        </Box>
      </Stack>
    </Flex>
    </form>
      </>
    );
  }
  
  