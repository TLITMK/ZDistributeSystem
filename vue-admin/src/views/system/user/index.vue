<template>
    <div class="app-container">



        <!--        table-->

        <el-table
                :data="tableData"
                style="width: 100%;margin-bottom: 20px;"
                row-key="id"
                border
                >

            <el-table-column
                    label="头像" width="60" fixed="left">
                <template slot-scope="scope">
                    <div class="avatar-container">
                        <div class="avatar-wrapper el-dropdown-selfdefine">

                            <img class="user-avatar" fit="cover" :src="scope.row.avatar" alt="" />

                        </div>
                    </div>
                </template>
            </el-table-column>
            <el-table-column
                    fixed="left"
                    prop="nickname"
                    label="姓名"
                    width="120"
                    sortable>

            </el-table-column>
            <el-table-column
                    prop="account"
                    label="账号"
                    width="120"
                    sortable>
            </el-table-column>
            <el-table-column
                    prop="email"
                    label="邮箱"
                    width="120"
                    sortable>
            </el-table-column>
            <el-table-column
                    prop="gender"
                    label="性别"
                    width="60"
                    sortable>
                <template slot-scope="scope">{{scope.row.gender===1?'男':scope.row.gender===2?'女':'未知'}}</template>
            </el-table-column>
            <el-table-column
                    prop="developer"
                    label="开发者"
                    width="60"
                    sortable>
                <template slot-scope="scope">
                    {{scope.row.developer?'是':'否'}}
                </template>
            </el-table-column>

            <el-table-column
                    prop="signature"
                    label="签名"
                    sortable>
            </el-table-column>
            <el-table-column
                    fixed="right"
                    label="操作"
                    width="120">
                <template slot-scope="scope">
                    <el-button
                            @click.native.prevent="deleteRow(scope, tableData)"
                            type="text"
                            size="small">
                        删除
                    </el-button>
                    <el-button
                            @click.native.prevent="addRow(scope, tableData)"
                            type="text"
                            size="small">
                        添加
                    </el-button>
                    <el-button
                            @click.native.prevent="editRow(scope, tableData)"
                            type="text"
                            size="small">
                        编辑
                    </el-button>

                </template>
            </el-table-column>
        </el-table>

        <!--        抽屉
        direction rtl / ltr / ttb / btt
        -->
        <el-drawer
                :title="drawerTitle"
                :show-close='true'
                :withHeader="true"
                :before-close="handleClose"
                :visible.sync="dialog"
                direction="ltr"
                custom-class=""
                ref="drawer"
        >
            <div class="drawer__content">
                <el-form :model="form">
                    <el-form-item :label="'id   '+form.id" >
                        <!--                        <p v-model="form.id"  >{{form.id}}</p>-->
                    </el-form-item>
                    <el-form-item label="账号" >
                        <el-input v-model="form.account" placeholder="不可为空" autocomplete="off"></el-input>
                    </el-form-item>
                    <el-form-item label="密码" v-if='isCreate'>
                        <el-input v-model="form.password" placeholder="不可为空" autocomplete="off"></el-input>
                    </el-form-item>
                    <el-form-item label="头像" >
                        <el-upload
                                style="margin-top: 50px;"
                                class="avatar-uploader"
                                action="http://127.0.0.1:9999/admin/api/user/admin_upload_avatar"
                                :headers="headers"
                                :show-file-list="false"
                                :on-success="handleAvatarSuccess"
                                :before-upload="beforeAvatarUpload">
                            <el-image
                                    class="avatar"
                                    style="width: 100px; height: 100px"
                                    v-if="form.avatar"
                                    :src="form.avatar"
                                    fit="contain"></el-image>
                            <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                        </el-upload>
                    </el-form-item>
                    <el-form-item label="邮箱" >
                        <el-input v-model="form.email" placeholder="不可为空"  autocomplete="off"></el-input>
                    </el-form-item>
                    <el-form-item label="性别" >
                        <el-input v-model="form.gender" placeholder="不可为空"  autocomplete="off"></el-input>
                    </el-form-item>
                    <el-form-item label="昵称" >
                        <el-input v-model="form.nickname" placeholder="不可为空"  autocomplete="off"></el-input>
                    </el-form-item>
                    <el-form-item label="签名" >
                        <el-input v-model="form.signature" autocomplete="off"></el-input>
                    </el-form-item>
                    <el-form-item label="开发者" >
                        <el-input v-model="form.developer" placeholder="不可为空"  autocomplete="off"></el-input>
                    </el-form-item>





                </el-form>
            </div>
            <div class="drawer__footer">
                <el-button type="danger" @click="cancelForm">关 闭</el-button>
                <el-button type="primary" @click="confirmForm()" :loading="loading">{{ loading ? '提交中 ...' : '确 定' }}</el-button>
            </div>
        </el-drawer>

    </div>
</template>
<script>
   import {user_admin_list,user_admin_edit,user_admin_upload_avatar,user_admin_del} from "../../../api/user";

   export default {
        name: "permission.index",
        data(){
            return {
                tableData:[],
                drawerTitle:'',
                form: {},
                formLabelWidth: '80px',
                timer:null,
                dialog: false,
                loading: false,
                selected_node:null,

                //图片
                dialogImageUrl: '',
                dialogVisible: false,
                disabled: false,
                isCreate:false,
            }
        },
       computed: {
           headers() {
               // return{
               //     'X-CSRF-TOKEN': document.get('meta[name="csrf-token"]').attr('content')// 直接从本地获取token就行
               // }
           }
       },
        created(){
            user_admin_list().then(res=>{
                this.tableData=res;
                //this.tableData=this.getMenu(res)
            })
        },
        methods:{
            //图片

            handleAvatarSuccess(res, file) {
                //上传文件 action 未使用 ，使用了before，因此此处无效
            },
            beforeAvatarUpload(file) {
                var _this=this;
                const isJPG = file.type === 'image/jpeg'||file.type==='image/png';
                const isLt2M = file.size / 1024 / 1024 < 2;

                if (!isJPG) {
                    this.$message.error('请选择图片文件');
                    return false;
                }
                if (!isLt2M) {
                    this.$message.error('上传头像图片大小不能超过 2MB!');
                    return false;
                }

                var reader=new FileReader();
                reader.readAsDataURL(file);
                reader.onload = () => {
                    this.form.avatar = reader.result
                    let fd=new FormData();
                    fd.append('file',reader.result);
                    fd.append('uid',this.form.id)
                    user_admin_upload_avatar(fd).then(res=>{
                        _this.$message({
                            message: res.data.msg,
                            type: 'success'
                        });
                        console.log(res)
                    })
                }





                return isJPG && isLt2M;
            },


            ///
            append(data) {
                this.selected_node=data;
            },

            remove(node, data) {
                const parent = node.parent;
                const children = parent.data.children || parent.data;
                const index = children.findIndex(d => d.id === data.id);
                children.splice(index, 1);
            },
            deleteRow(row,data) {
                console.log(row)
                console.log(row.row)
                // return;
                this.$confirm('确定要删除 【'+row.row.nickname+'】 吗？')
                    .then(_ => {
                        user_admin_del({id:row.row.id}).then(res=>{
                            if(res.err_code===0){
                                this.$message({
                                    message:res.msg,
                                    type:'success'
                                })
                                user_admin_list().then(res=>{
                                    this.tableData=res
                                })
                                // const parent = row.row.parent;
                                // const children = parent.children;
                                // const index = children.findIndex(d => d.id === data.id);
                                // children.splice(index, 1);

                            }
                            else{
                                this.$message.error(res.msg)
                            }
                        })

                    })
                    .catch(
                        _ => {}
                    );


            },
            addRow(row) {
                this.drawerTitle='添加'
                this.isCreate=true;
                this.form={
                    parent_id:row.row.id,
                    guard_name:'web',
                    level:row.row.level+1

                };
                this.dialog=true;
                console.log(row.$index)
                console.log(row.row)
            },
            editRow(row){
                this.drawerTitle='编辑'
                this.isCreate=false;
                this.form=row.row;
                console.log(this.form)

                this.dialog=true;
            },
            getMenu(permissions,parent_id=0,parent=null){
                var _this=this;
                var arr=[];
                permissions.forEach((v,k)=>{
                    if(v.parent_id===parent_id){
                        v.children=_this.getMenu(permissions,v.id,v);
                        v.parent=[];
                        v.parent.push(parent)
                        arr.push(v)
                    }
                })

                return arr;
            },

            handleClose(done) {
                if (this.loading) {
                    return;
                }
                done();
            },
            cancelForm() {
                this.loading = false;
                this.dialog = false;
                clearTimeout(this.timer);
            },
            confirmForm(){
                var _this=this;
                this.$confirm('确定要提交表单吗？')
                    .then(_ => {
                        this.loading = true;
                        console.log(this.form)
                        this.form.children=null;
                        this.form.parent=null;
                        this.form.avatar=null;
                        user_admin_edit(this.form).then(res=>{
                            _this.loading = false;
                            _this.$refs.drawer.closeDrawer();//关闭
                            if(res.err_code===0){
                                this.$message({
                                    message: res.msg,
                                    type: 'success'
                                });
                                user_admin_list().then(res=>{
                                    this.tableData=res
                                })
                            }
                            else{
                                this.$message.error(res.msg);
                            }

                            console.log(res);

                        });

                    })
                    .catch(
                        _ => {}
                    );

            },

        }
    }
</script>

<style scoped>

</style>