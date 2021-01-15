<template>
    <div class="app-container">



<!--        table-->

        <el-table
                :data="tableData"
                style="width: 100%;margin-bottom: 20px;"
                row-key="id"
                border
                default-expand-all
                :tree-props="{children: 'children', hasChildren: 'hasChildren'}">

            <el-table-column
                    fixed=""
                    prop="title"
                    label="title"
                    width="180"
                    sortable>
            </el-table-column>
            <el-table-column
                    prop="id"
                    label="id"
                    sortable>
            </el-table-column>
            <el-table-column
                    prop="parent_id"
                    label="parent_id"
                    sortable>
            </el-table-column>

            <el-table-column
                    prop="path"
                    label="path"
                    sortable
                    width="180">
            </el-table-column>
            <el-table-column
                    prop="name"
                    label="name"
                    sortable
                    width="180">
            </el-table-column>

            <el-table-column
                    prop="guard_name"
                    label="guard_name"
                    sortable
                    width="180">
            </el-table-column>

            <el-table-column
                    prop="icon"
                    label="icon"
                    sortable
                    width="180">
            </el-table-column>
            <el-table-column
                    prop="component"
                    label="component"
                    sortable
                    width="180">
            </el-table-column>
            <el-table-column
                    prop="hidden"
                    label="hidden"
                    sortable
                    width="180">
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
                    <el-form-item label="title" >
                        <el-input v-model="form.title" placeholder="不可为空" autocomplete="off"></el-input>
                    </el-form-item>
                    <el-form-item label="parent_id" >
                        <el-input v-model="form.parent_id" placeholder="不可为空"  autocomplete="off"></el-input>
                    </el-form-item>
                    <el-form-item label="level" >
                        <el-input v-model="form.level" placeholder="不可为空"  autocomplete="off"></el-input>
                    </el-form-item>
                    <el-form-item label="name" >
                        <el-input v-model="form.name" placeholder="不可为空"  autocomplete="off"></el-input>
                    </el-form-item>
                    <el-form-item label="guard_name" >
                        <el-input v-model="form.guard_name" placeholder="不可为空"  autocomplete="off"></el-input>
                    </el-form-item>
                    <el-form-item label="path" >
                        <el-input v-model="form.path" placeholder="不可为空"  autocomplete="off"></el-input>
                    </el-form-item>
                    <el-form-item label="icon" >
                        <el-input v-model="form.icon" autocomplete="off"></el-input>
                    </el-form-item>
                    <el-form-item label="component" >
                        <el-input v-model="form.component" placeholder="不可为空"  autocomplete="off"></el-input>
                    </el-form-item>
                    <el-form-item label="breadcrumb" >
                        <el-input v-model="form.breadcrumb" autocomplete="off"></el-input>
                    </el-form-item>
                    <el-form-item label="hidden" >
                        <el-input v-model="form.hidden" autocomplete="off"></el-input>
                    </el-form-item>
                    <el-form-item label="noCache" >
                        <el-input v-model="form.noCache" autocomplete="off"></el-input>
                    </el-form-item>
                    <el-form-item label="redirect" >
                        <el-input v-model="form.redirect" autocomplete="off"></el-input>
                    </el-form-item>
                    <el-form-item label="activeMenu" >
                        <el-input v-model="form.activeMenu" autocomplete="off"></el-input>
                    </el-form-item>
                    <el-form-item label="sort" >
                        <el-input v-model="form.sort" autocomplete="off"></el-input>
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
    import {permission_all,permission_add,permission_del} from "../../../api/permission";

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
            }
        },
        created(){
            permission_all().then(res=>{
                this.tableData=this.getMenu(res)
            })
        },
        methods:{
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
                this.$confirm('确定要删除 【'+row.row.title+'】 权限吗？')
                    .then(_ => {
                        permission_del({id:row.row.id}).then(res=>{
                            if(res.err_code===0){
                                this.$message({
                                    message:res.msg,
                                    type:'success'
                                })
                                const parent = row.row.parent;
                                const children = parent.children;
                                const index = children.findIndex(d => d.id === data.id);
                                children.splice(index, 1);

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
                        permission_add(this.form).then(res=>{
                            _this.loading = false;
                            _this.$refs.drawer.closeDrawer();//关闭
                            if(res.err_code===0){
                                this.$message({
                                    message: res.msg,
                                    type: 'success'
                                });
                                permission_all().then(res=>{
                                    this.tableData=this.getMenu(res)
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