<template>
    <el-container>
        <el-header>
            <h1>角色管理</h1>
        </el-header>
        <!--        table-->
        <el-main>
            <div class="block">
                <el-input placeholder="请输入内容" v-model="searchValue"  style="width:50%" clearable @clear="searchClear">
                    <el-select v-model="searchType" style="width: 100px;" slot="prepend" placeholder="请选择">
                        <el-option label="角色" value="name"></el-option>
                        <el-option label="名称" value="title"></el-option>
                    </el-select>
                    <el-button slot="append" icon="el-icon-search" @click="getList(tableData.currentPage,tableData.per_page)"></el-button>
                </el-input>
            </div>
            <el-table
                    :data="tableData.data"
                    style="width: 100%;margin-bottom: 20px;"
                    row-key="id"
                    border
            >

                <el-table-column
                        prop="name"
                        label="角色"
                        sortable>

                </el-table-column>
                <el-table-column
                        prop="title"
                        label="名称"
                        sortable>
                </el-table-column>
                <el-table-column
                        prop="guard_name"
                        label="守卫类型"
                        sortable>
                </el-table-column>
                <el-table-column
                        fixed="right"
                        label="操作"
                        width="120">
                    <template slot-scope="scope">
                        <el-button
                                @click.native.prevent="deleteRow(scope, tableData.data)"
                                type="text"
                                size="small">
                            删除
                        </el-button>
                        <el-button
                                @click.native.prevent="addRow(scope, tableData.data)"
                                type="text"
                                size="small">
                            添加
                        </el-button>
                        <el-button
                                @click.native.prevent="editRow(scope, tableData.data)"
                                type="text"
                                size="small">
                            编辑
                        </el-button>

                    </template>
                </el-table-column>
            </el-table>
            <div class="block" style="float: right;">
                <el-pagination
                        @size-change="handleSizeChange"
                        @current-change="handleCurrentChange"
                        :current-page="tableData.currentPage"
                        :page-sizes="[10, 20, 50, 100]"
                        :page-size="tableData.per_page"
                        layout="total, sizes, prev, pager, next, jumper"
                        :total="tableData.total">
                </el-pagination>
            </div>
        </el-main>
        <el-footer></el-footer>

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
                    <el-form-item :label="'id   '+form.id" v-if="!isCreate">
                    </el-form-item>
                    <el-form-item label="角色" >
                        <el-input v-model="form.name" placeholder="不可为空" autocomplete="off"></el-input>
                    </el-form-item>
                    <el-form-item label="名称" >
                        <el-input v-model="form.title" placeholder="不可为空" autocomplete="off"></el-input>
                    </el-form-item>
                    <el-form-item label="守卫类型" >
                        <el-input v-model="form.guard_name" placeholder="不可为空" autocomplete="off"></el-input>
                    </el-form-item>

                </el-form>
            </div>
            <div class="drawer__footer">
                <el-button type="danger" @click="cancelForm">关 闭</el-button>
                <el-button type="primary" @click="confirmForm()" :loading="loading">{{ loading ? '提交中 ...' : '确 定' }}</el-button>
            </div>
        </el-drawer>

    </el-container>
</template>
<script>
   import {deleteRole,editRoles,getRoles} from "../../../api/role";

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
                searchType:'account',
                searchValue:''
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
            this.getList(1,10);
        },
        methods:{
            searchClear(){
                this.searchValue=''
                this.getList(this.tableData.currentPage,this.tableData.per_page)
            },
            handleSizeChange(val) {
                this.getList(this.tableData.currentPage,val)
            },
            handleCurrentChange(val) {
                this.getList(val,this.tableData.per_page)
            },
            getList(page,pageSize){
                let params={
                    page:page,
                    pageSize:pageSize,
                    searchType: this.searchType,
                    searchValue: this.searchValue
                }
                getRoles(params).then(res=>{
                    this.tableData=res;
                })
            },

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



            deleteRow(row,data) {
                console.log(row)
                console.log(row.row)
                // return;
                this.$confirm('确定要删除 【'+row.row.nickname+'】 吗？')
                    .then(_ => {
                        deleteRole({id:row.row.id}).then(res=>{
                            if(res.err_code===0){
                                this.$message({
                                    message:res.msg,
                                    type:'success'
                                })
                                this.getList(1,15)
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
                    guard_name:'web',

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
                        // this.loading = true;
                        console.log(this.form)
                        this.form.children=null;
                        this.form.parent=null;
                        this.form.avatar=null;
                        editRoles(this.form).then(res=>{
                            // _this.loading = false;
                            if(res.err_code===0){
                                this.$message({
                                    message: res.msg,
                                    type: 'success'
                                });
                                this.getList(1,15)
                                _this.$refs.drawer.closeDrawer();//关闭
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