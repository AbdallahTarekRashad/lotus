<?php
include_once 'DB.php';
/*
this class about everything about product and table product in database
*/
class Product
{
    /*
    **function addproduct insert new product in database and return id of it if it inserted
    **if not inserted return false
    **$productInfo is an array with product information like
    **name_en,name_ar,script_en,script_ar,price,image->image saved in Resources/ProductImages,
    **video->id of video in youtube
    */
    public function addProduct ($productInfo)
    {
        $db = new DB();
        $querySelect = "SELECT * 
                        FROM 
                            `product` 
                        WHERE 
                            `name_en` ='".$productInfo['name_en']."'
                        OR 
                            `name_ar` = '".$productInfo['name_ar']."'";
        $result = $db->prepare($querySelect);
        $result->execute();
        if($result->rowCount() > 0)
        {
            return false;
        }
        else
        {
            $queryInsert = "INSERT INTO 
                                `product` 
                                (`name_en`, 
                                `name_ar`, 
                                `script_en`, 
                                `script_ar`, 
                                `price`, 
                                `image`, 
                                `video`,
                                `category`) 
                            VALUES 
                                ('".$productInfo['name_en'].
                                "', '".$productInfo['name_ar'].
                                "', '".$productInfo['script_en'].
                                "', '".$productInfo['script_ar'].
                                "', '".$productInfo['price'].
                                "', '".$productInfo['image'].
                                "', '".$productInfo['video'].
                                "', '".$productInfo['category']."')";
            $sql = $db->prepare($queryInsert);
            $result = $sql->execute();
            if($result)
            {
                $querySelect = "SELECT 
                                    `id` 
                                FROM 
                                    `product` 
                                WHERE 
                                    `name_en` ='".$productInfo['name_en']."'";
                $sql = $db->prepare($querySelect);
                $sql->execute();
                $data = $sql->fetchAll();
                $db = NULL;
                return $data[0]['id'];
            }
            else
            {
                $db = NULL;
                return 'Not Inserted';
            }
        }
    }
    /*
    **get product information by id of product
    */
    public function getProduct($id)
    {
        $db = new DB();
        $query = "SELECT * FROM `product` WHERE `id` =".$id;
        $result = $db->prepare($query);
        $result->execute();
        $ProductData = $result->fetchAll();
        $db = NULL;
        return $ProductData;
    }
    /*
    **update one column in one time 
    **$id id of product
    **$col column i want to update
    **$data data i want to set
    **if updated reurn true else return false
    */
    public function updateProduct($id,$col,$data)
    {
        $db = new DB(); 
        $query = "UPDATE `product` SET `".$col."` = '".$data."' WHERE `id` = ".$id;
        $stm = $db->prepare($query);
        if($stm->execute())
        {
            $db = NULL;
            return TRUE;
        } 
        else 
        {
            $db = NULL;
            return FALSE; 
        }

    }
    /*
    **delete one product in one time by id
    **if deleted return true else return false
    */
    public function deleteProduct($id)
    {
        $db = new DB(); 
        $query = "DELETE FROM `product` WHERE `id`= ".$id;
        $stm = $db->prepare($query);
        if($stm->execute())
        {
            $db = NULL;
            return TRUE;   
        } 
        else 
        {
            $db = NULL;
            return false;    
        }
    }
    /*
    **get one column from specific product by id
    */
    public function selectProduct($id,$col)
    {
        $db = new DB();
        $query = "SELECT ".$col." FROM `product` WHERE `id` =".$id;
        $db->arabic();
        $result = $db->prepare($query);
        $result->execute();
        $ProductData = $result->fetchAll();
        $db = NULL;
        return $ProductData;
    }
    /*
    **function visible if product has in visible column 1 make it 0 and vice versa 
    **$id the id of product
    */
    public function visible($id)
    {
        $visible = $this->selectProduct($id,'visible');
        if($visible[0]['visible'] == 1)
        {
            $this->updateProduct($id,'visible',0);
        }
        else
        {
            $this->updateProduct($id,'visible',1);
        }
    }
    /*
    **function GetProductByLIMIT get 10 rows from product 
    **if $pageid = 5 the limet get from 40 to 50
    */
    public function GetProductByLIMIT($pageid)
    {
        $db = new DB();
        $start = 12*($pageid-1);
        $row = 12;
        $query = "SELECT * FROM `product` WHERE `visible` = 1 LIMIT $start,$row";
        $stm = $db->prepare($query);
        $stm->execute();
        $result = $stm->fetchAll();
        $db = NULL;
        return $result;
    }
    /*
    **function GetProductByLIMIT get 10 rows from product in specific category 
    **if $pageid = 5 the limet get from 40 to 50
    */
    public function GetProductByLIMITCtegory($pageid,$categoryId)
    {
        $db = new DB();
        $start = 12*($pageid-1);
        $row = 12;
        $query = "SELECT * FROM `product` WHERE `category`= $categoryId AND `visible` = 1 LIMIT $start,$row ";
        $stm = $db->prepare($query);
        $stm->execute();
        $result = $stm->fetchAll();
        $db = NULL;
        return $result;
    }
    /*
    **function getNumberOfProduct return number of all product
    */
    public function getNumberOfProduct()
    {
        $query = "SELECT * FROM `product` WHERE `visible` = 1 ";
        $db = new DB();
        $result = $db->prepare($query);
        $result->execute();
        $data = $result->rowCount();
        return $data;
    }
    /*
    **function getNumberOfProductOfCategory return number of products in specific category
    **$categoryId the category id
    */
    public function getNumberOfProductOfCategory($categoryId)
    {
        $query = "SELECT * FROM `product` WHERE `category`= $categoryId AND `visible` = 1";
        $db = new DB();
        $result = $db->prepare($query);
        $result->execute();
        $data = $result->rowCount();
        return $data;
    }
    public function GetProductByLIMITAdmin($pageid)
    {
        $db = new DB();
        $start = 12*($pageid-1);
        $row = 12;
        $query = "SELECT * FROM `product` LIMIT $start,$row";
        $stm = $db->prepare($query);
        $stm->execute();
        $result = $stm->fetchAll();
        $db = NULL;
        return $result;
    }
    /*
    **function GetProductByLIMIT get 10 rows from product in specific category 
    **if $pageid = 5 the limet get from 40 to 50
    */
    public function GetProductByLIMITCtegoryAdmin($pageid,$categoryId)
    {
        $db = new DB();
        $start = 12*($pageid-1);
        $row = 12;
        $query = "SELECT * FROM `product` WHERE `category`= $categoryId  LIMIT $start,$row ";
        $stm = $db->prepare($query);
        $stm->execute();
        $result = $stm->fetchAll();
        $db = NULL;
        return $result;
    }
    /*
    **function getNumberOfProduct return number of all product
    */
    public function getNumberOfProductAdmin()
    {
        $query = "SELECT * FROM  `product`";
        $db = new DB();
        $result = $db->prepare($query);
        $result->execute();
        $data = $result->rowCount();
        return $data;
    }
    /*
    **function getNumberOfProductOfCategory return number of products in specific category
    **$categoryId the category id
    */
    public function getNumberOfProductOfCategoryAdmin($categoryId)
    {
        $query = "SELECT * FROM `product` WHERE `category`= $categoryId ";
        $db = new DB();
        $result = $db->prepare($query);
        $result->execute();
        $data = $result->rowCount();
        return $data;
    }
}