library(RODBC)
conn <- odbcConnect("BigData", uid="team1" , pwd="yb102") 
data_table <- sqlTables(conn) 


df <- sqlQuery(conn, "select user_id, on_thing_id, type, is_purchased, is_liked, genre from train_model")
close(conn)
names (df) <-c ("user","item","type", "buy","like","genre") 
disc = df[df$type == "disc" , -3]

# Read data set
#original <-read.csv (file = "train_model.csv", header = FALSE)
#names (original) <-c ("id","user","item","type", "buy","like","genre") 

#篩選出disc
#disc = original[original$type == "disc" , c(2,3,5,6,7)]

#依buy和favorite給定分數
disc = cbind(disc,5)
disc[which(disc[,3]==1&disc[,4]==0),6]=3
disc[which(disc[,3]==0&disc[,4]==1),6]=2

colnames(disc) = c('user','item','buy','like','genre','score')

disc = disc[, c(1,2,5,6)]


#取disc分類第一群
cluster_1 = disc[disc$genre=="2", -3]



# Calculated User Lists
usersUnique <-function () {
  users <-unique (cluster_1 $ user)
  users [order (users)]
}

# Calculation Method Product List
itemsUnique <-function () {
  items <-unique (cluster_1 $ item)
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
  len = length(which(cluster_1$user==users[i]))
  
  #算出使用者users[i]曾經評價過的所有物品的和
  sum = sum(cluster_1[which(cluster_1$user==users[i]),]$score)
  
  #m矩正為使用者與其平均分
  m[i,1] = users[i]
  m[i,2] = round(sum/len,digits=2) 
}



f = function(user){
  #找出和輸入的user同群的user
  user_avg_score = m[which(m[,1] == user),2]
  r = round(user_avg_score,digits=0)
  members = m[which(round(m[,2],digits=0) == r),]
    
  score_of_members = matrix(- members[,2],byrow=T,nrow=length(items),ncol=length(members[,1]))
  
  #找出同群user對物品J的評價
  for(i in 1:length(items)){
    item = items[i]
    for(j in 1:length(members[,1])){           
      member = which(cluster_1$user==members[j,1])
      for(k in member){
        if(cluster_1[k,2]==item){
          obj = cluster_1[k,3]         
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



test = f(311)






