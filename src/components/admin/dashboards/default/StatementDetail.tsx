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

  import { convertDate } from 'utils';

  import useSWR from "swr";
 
  import * as React from 'react';
  import { useRouter,useParams } from 'next/navigation';
  import { useEffect, useState } from 'react';

import { getToken } from 'app/actions/userInfof';

  // Assets
  type Authens = {
    username: string
    fullname: string
    banknumber:string
    bankname:string
    password: string
    balance:number;
    provider_password:string
    createdAt:string;
    transactionamount:string
  }

      
  //const columnHelper = createColumnHelper<RowObj>();
  const fetcher = (url:string) => fetch(url,{ method: 'POST',
    headers: {
    'Accept': 'application/json',
    'Content-Type': 'application/json',
    'Authorization': 'Bearer ' +  getToken()// localStorage.getItem('token')
    },
   body: JSON.stringify({"startdate":new Date(new Date().setDate(new Date().getDate() - 7)).toJSON().slice(0, 10),"stopdate":new Date(new Date().setDate(new Date().getDate() + 7)).toJSON().slice(0, 10),"prefix":"all","statement_type":"Withdraw","status":"all"})
  }).then((res) => res.json());
  // const columns = columnsDataCheck;
  export default function StatementsDetail(props:any) {
    // const { tableData } = props;
    //const params = useParams()
    const params = useParams();
    const router =  useRouter()
    // const [sorting, setSorting] = useState<SortingState>([]);
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
    //const router = useRouter()
    //const [transfers,setTransfers] = useState<RowObj[]>([])
     
    //const [data, setData] =  useState(() => [...transfers]);
    // const [errorx, setError] = useState<string | null>(null);
    // const [show, setShow] = React.useState(false);
    // const [showb, setShowb] = React.useState(false);
    // const [showc, setShowc] = React.useState(false);
    // const handleClick = () => setShow(!show);
    // const {
    //   register,
    //   handleSubmit,
    //   watch,
    //   formState: { errors },
    // } = useForm<Authens>()
 
    // const [columnFilters, setColumnFilters] = useState<ColumnFiltersState>([]);
   
    // const [globalFilter, setGlobalFilter] =  useState('');

    const {id} = params

    const { data, error, isLoading } = useSWR(id ? `https://report.tsxbet.net/reports/transactions/${id}` : null,
    fetcher
    );

    const back = () =>{
        router.back()
    }
    const {
        register,
        handleSubmit,
        watch,
        formState: { errors },
      } = useForm<Authens>()

   
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
       
        
        <Box
          rounded={'lg'}
          bg={useColorModeValue('white', 'gray.700')}
          boxShadow={'lg'}
          p={8}>
               <FormControl id="createdat" >
                  <FormLabel>วันที่ถอน</FormLabel>
                  <Input type="text" defaultValue={convertDate(data.data[0].createdAt)} {...register("createdAt", { disabled: true })} />
                </FormControl>
            <FormControl id="fullname" >
                  <FormLabel>ชื่อ นามสกุล</FormLabel>
                  <Input type="text" defaultValue={data.data[0].fullname} {...register("fullname", { disabled: true })} />
                </FormControl>
            <FormControl id="username" >
              <FormLabel>ชื่อผู้ใช้งาน</FormLabel>
              <Input type="text" defaultValue={data.data[0].username} {...register("username", { disabled: true })}/>
            </FormControl>
            <FormControl id="bankname" >
                  <FormLabel>ธนาคาร</FormLabel>
                  <Input type="text" defaultValue={data.data[0].bankname} {...register("bankname", { disabled: true })} />
            </FormControl>
            <FormControl id="banknumber" >
                  <FormLabel>หมายเลขบัญชี</FormLabel>
                  <Input type="text"  defaultValue={data.data[0].banknumber} {...register("banknumber", { disabled: true })} />
            </FormControl>
       
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
            <Input type="text" defaultValue={data.data[0].transactionamount}  {...register("transactionamount", { disabled: true })}/>
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
                   onClick={()=>{router.replace('/admin/transactions')}}
                    >
                  ย้อนกลับ
                  </Button>
        </HStack>
        </Box>
 
      </Stack>
    </Flex>
    </>
    );
  }
  
  