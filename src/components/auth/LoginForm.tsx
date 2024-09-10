 
'use client';
import React from 'react'
import { useForm } from 'react-hook-form';
import { useRouter } from 'next/navigation';
import { useState,useEffect } from 'react';
import Swal from 'sweetalert2'
 
import withReactContent from 'sweetalert2-react-content';

import { Flex,Box,Heading,  
  FormControl,
  FormLabel,
  Button,
  Center,
  Icon,
  Image,
  Input,
  InputGroup,
  InputRightElement,
  Text,
  VStack,
  Stack,
  useColorModeValue, } from '@chakra-ui/react'

  import { FcGoogle } from 'react-icons/fc';
  import { MdOutlineRemoveRedEye } from 'react-icons/md';
  import { RiEyeCloseLine } from 'react-icons/ri';
  import DefaultAuthLayout from 'layouts/auth/Default';
import { login } from 'app/actions/auth';
 

 
//import { useFormState } from "react-dom";

type LoginFormInputs = {
    username: string;
    password: string;
};
type Authens = {
  username: string
  password: string
}
const MySwal = withReactContent(Swal);

export default  function LoginForm() {
     
    const [error, setError] = useState('');
    const router = useRouter();
    const textColor = useColorModeValue('navy.700', 'white');
    const textColorSecondary = 'gray.400';
    const textColorDetails = useColorModeValue('navy.700', 'secondaryGray.600');
    const textColorBrand = useColorModeValue('brand.500', 'white');
    const brandStars = useColorModeValue('brand.500', 'brand.400');
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
    const [show, setShow] = React.useState(false);
    const handleClick = () => setShow(!show);
    const [isLogged,setIsLogged] = useState(false)
 
 
    const {
      register,
      handleSubmit,
      watch,
      formState: { errors },
    } = useForm<Authens>()
 
    const onSubmit = async (data: Authens) => {
     const result = await login(data.username,data.password)
     //.then((result)=>{
  
        if(!result.status){
            MySwal.fire({
              title: 'เข้าสู่ระบบ',
              text: 'ชื่อผู้ใช้ หรือ รหัสผ่านไม่ถูกต้อง!.',
              icon: 'error',
              confirmButtonText: 'ตกลง',
              
            });
           
          }else {
            router.push("/")
          }
       
      };
 

    return (
      <>
      <form onSubmit={handleSubmit(onSubmit)}> 
      <DefaultAuthLayout illustrationBackground={'/img/auth/auth.png'}>
      <Flex
      maxW={{ base: '100%', md: 'max-content' }}
      w="100%"
      mx={{ base: 'auto', lg: '0px' }}
      me="auto"
      h="100%"
      alignItems="start"
      justifyContent="center"
      mb={{ base: '30px', md: '60px' }}
      px={{ base: '25px', md: '0px' }}
      mt={{ base: '40px', md: '14vh' }}
      flexDirection="column"
    >
     
 <Flex 
  w="100%"
  mx={{ base: 'auto', lg: '0px' }}
  me="auto"

  alignItems="start"
  justifyContent="center"
 >
        <Heading color={textColor} fontSize="36px" mb="20px">
        <Image
				boxSize='100px'
				objectFit='cover'
				src= '/img/layout/human.jfif'
				alt='Dan Abramov'
				mb='20px'
			/>
        </Heading>
        
        </Flex>
      
      
 
     
      <Flex
        zIndex="2"
        direction="column"
        w={{ base: '100%', md: '420px' }}
        maxW="100%"
        background="transparent"
        borderRadius="15px"
        mx={{ base: 'auto', lg: 'unset' }}
        me="auto"
        mb={{ base: '20px', md: 'auto' }}
      >
        <FormControl>
            <FormLabel
              display="flex"
              ms="4px"
              fontSize="sm"
              fontWeight="500"
              color={textColor}
              mb="8px"
            >
              Username<Text color={brandStars}>*</Text>
            </FormLabel>
            <Input
              isRequired={true}
              variant="auth"
              fontSize="sm"
              ms={{ base: '0px', md: '0px' }}
              type="text"
              placeholder="Enter your username"
              mb="24px"
              fontWeight="500"
              size="lg"
              id="username"
              {...register("username", { required: true })}
            />
            <FormLabel
              ms="4px"
              fontSize="sm"
              fontWeight="500"
              color={textColor}
              display="flex"
            >
              Password<Text color={brandStars}>*</Text>
            </FormLabel>
            <InputGroup size="md">
              <Input
                isRequired={true}
                fontSize="sm"
                placeholder="Min. 8 characters"
                mb="24px"
                size="lg"
                type={show ? 'text' : 'password'}
                variant="auth"
                id="password"
                {...register("password", { required: true })}
              />
              <InputRightElement display="flex" alignItems="center" mt="4px">
                <Icon
                  color={textColorSecondary}
                  _hover={{ cursor: 'pointer' }}
                  as={show ? RiEyeCloseLine : MdOutlineRemoveRedEye}
                  onClick={handleClick}
                />
              </InputRightElement>
            </InputGroup>
            </FormControl>
            <Button
              fontSize="sm"
              variant="brand"
              fontWeight="500"
              w="100%"
              h="50"
              mb="24px"
              type='submit'
            >
              Sign In
            </Button>
    </Flex>
    </Flex>
  
    </DefaultAuthLayout>
    </form>
    </>
    );
} 
 