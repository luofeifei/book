test1
test2
echo "# test" >> README.md
//新建一个远程仓库
curl -u 'anrenluofeifei@163.com' https://api.github.com/user/repos -d '{"name":"book"}'
git init
git add .
git commit -m "test"
git remote add origin https://github.com/luofeifei/book.git
git push -u origin master