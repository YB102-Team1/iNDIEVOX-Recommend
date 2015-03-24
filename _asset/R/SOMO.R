library(RODBC)
conn <- odbcConnect("BigData", uid="team1" , pwd="yb102") 
data_table <- sqlTables(conn) 


df <- sqlQuery(conn, "select id, user_id, on_thing_id, type, is_purchased, is_liked, genre from train_model")
close(conn)
names (df) <-c ("id","user","item","type", "buy","like","genre") 
disc = df[df$type == "disc" , -4]

train_disc = disc[which(disc$id%%5 !=0),]
train_disc = train_disc[,-1]
test_disc = disc[which(disc$id%%5 ==0),]
test_disc = test_disc[,-1]
#Read data set
#original <-read.csv (file = "train_model.csv", header = FALSE)
#names (original) <-c ("id","user","item","type", "buy","like","genre") 

#篩選出disc
#disc = original[original$type == "song" , c(2,3,5,6,7)]

#依buy和favorite給定分數
train_disc = cbind(disc,5)
train_disc[which(train_disc$buy==1&train_disc$like==0),6]=3
train_disc[which(train_disc$buy==0&train_disc$like==1),6]=2

train_disc = train_disc[, c(2,3,6,7)]
colnames(train_disc) = c('user','item','genre','score')


#取disc分類
cluster = train_disc[train_disc$genre=="1", -3]

#假設某user正在觀看某item
user=1
item=808
if(length(which(cluster$user==user))==0){
  cluster = rbind(cluster,c(user,item))
  cluster[-1,3]=5
}


# Calculated User Lists
usersUnique <-function () {
  users <-unique (cluster $ user)
  users [order (users)]
}

# Calculation Method Product List
itemsUnique <-function () {
  items <-unique (cluster $ item)
  items [order (items)]
}

# User Lists
users <-usersUnique () 

# Product List
items <-itemsUnique () 

#將使用者分群
m=matrix (rep (0, length(users) * 2), nrow = length(users))

for(i in 1:length(users)){ 
  #算出使用者users[i]的個數
  len = length(which(cluster$user==users[i]))
  
  #算出使用者users[i]曾經評價過的所有物品的和
  sum = sum(cluster[which(cluster$user==users[i]),3])
  
  #m矩正為使用者與其平均分
  m[i,1] = users[i]
  m[i,2] = round(sum/len,digits=2) 
}


#矩陣相乘
f = function(user){
  
  #找出和輸入的user同群的user(四捨五入同分的user)
  user_avg_score = m[which(m[,1] == user),2]
  r = round(user_avg_score,digits=0)
  members = m[which(round(m[,2],digits=0) == r),]
  
  score_of_members = matrix(- members[,2],byrow=T,nrow=length(items),ncol=length(members[,1]))
  
  #找出同群user對物品J的評價
  for(i in 1:length(items)){
    item = items[i]
    for(j in 1:length(members[,1])){           
      member = which(cluster$user==members[j,1])
      for(k in member){
        if(cluster[k,2]==item){
          obj = cluster[k,3]         
          score_of_members[i,j] = obj  + score_of_members[i,j]
        }
      }     
    }
  }
  w = matrix(0,nrow=length(members[,1]))
  
  w_1 = 0
  w_2 = 0
  a=which(members[,1]==user)
  for(i in 1:length(members[,1])){
    for(j in 1:length(items)){                
      #     if(score_of_members[j,i]!=0 && score_of_members[j,a]!=0){
      w_1 = w_1 + score_of_members[j,a]*score_of_members[j,i]
      w_2 = (w_2 + (score_of_members[j,a]*score_of_members[j,i])^2)^(1/2)
      #     }       
    }
    w[i] = w_1/w_2      
  }
  
  va = matrix(members[members[,1]==user,2],nrow=length(items))
  k = 1/colSums(w)
  result=va+ k*(score_of_members %*% w)
  result = round((va+ k*(score_of_members %*% w))*10,digits=2)
  result_items = matrix(items,nrow=length(items))
  result = cbind(matrix(items,nrow=length(items)),result)
  colnames(result)=c('item','pref')
  result = result[order(result[,2],decreasing=T),]
  return(result) 
  
}



test2 = f(1)






