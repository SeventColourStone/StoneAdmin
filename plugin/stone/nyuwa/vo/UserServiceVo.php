<?php


namespace plugin\stone\nyuwa\vo;



class UserServiceVo
{
    /**
     * 用户名
     * @var 
     */
    protected  $username;

    /**
     * 密码
     * @var 
     */
    protected  $password;

    /**
     * 手机
     */
    protected  $phone;

    /**
     * 邮箱
     */
    protected  $email;

    /**
     * 验证码
     * @var 
     */
    protected  $verifyCode;

    /**
     * 其他数据
     * @var 
     */
    protected  $other;

    /**
     * @return 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param  $username
     */
    public function setUsername( $username): void
    {
        $this->username = $username;
    }

    /**
     * @return 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param  $password
     */
    public function setPassword( $password): void
    {
        $this->password = $password;
    }

    /**
     * @return 
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param  $phone
     */
    public function setPhone( $phone): void
    {
        $this->phone = $phone;
    }

    /**
     * @return 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param  $email
     */
    public function setEmail( $email): void
    {
        $this->email = $email;
    }

    /**
     * @return 
     */
    public function getVerifyCode()
    {
        return $this->verifyCode;
    }

    /**
     * @param  $verifyCode
     */
    public function setVerifyCode( $verifyCode): void
    {
        $this->verifyCode = $verifyCode;
    }

    /**
     * @return 
     */
    public function getOther()
    {
        return $this->other;
    }

    /**
     * @param  $other
     */
    public function setOther( $other): void
    {
        $this->other = $other;
    }
}