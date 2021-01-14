<template>
    <div class="app-container">


        <h1>scoped slot</h1>
        <el-tree
                :data="data"
                show-checkbox
                node-key="id"
                :default-expanded-keys="[2, 3]"
                :default-checked-keys="[5]"
                :expand-on-click-node="false"
                :props="defaultProps">
            <span class="custom-tree-node" slot-scope="{ node, data }">
                <span>{{ node.label }}
                </span>
                <el-button type="text" size="mini"  @click="() => append(data)">append</el-button>
                <el-button type="text" size="mini"  @click="() => remove(node, data)">remove</el-button>
            </span>
        </el-tree>
        <el-drawer
                title="我嵌套了 Form !"
                :before-close="handleClose"
                :visible.sync="dialog"
                direction="ltr"
                custom-class="demo-drawer"
                ref="drawer"
        >
            <div class="drawer__content">
                <el-form :model="form">
                    <el-form-item label="权限名称" >
                        <el-input v-model="form.name" autocomplete="off"></el-input>
                    </el-form-item>
                    <el-form-item label="权限id" >
                        <el-input v-model="form.id" autocomplete="off"></el-input>
                    </el-form-item>
                </el-form>
                <div class="drawer__footer">
                    <el-button @click="cancelForm">取 消</el-button>
                    <el-button type="primary" @click="$refs.drawer.closeDrawer()" :loading="loading">{{ loading ? '提交中 ...' : '确 定' }}</el-button>
                </div>
            </div>
        </el-drawer>

    </div>
</template>
<script>
    export default {
        name: "permission.index",
        data(){
            return {
                data:[
                    {
                    id: 1,
                    label: '一级 1',
                    children: [{
                        id: 4,
                        label: '二级 1-1',
                        children: [{
                            id: 9,
                            label: '三级 1-1-1'
                        }, {
                            id: 10,
                            label: '三级 1-1-2'
                        }]
                    }]
                }, {
                    id: 2,
                    label: '一级 2',
                    children: [{
                        id: 5,
                        label: '二级 2-1'
                    }, {
                        id: 6,
                        label: '二级 2-2'
                    }]
                }, {
                    id: 3,
                    label: '一级 3',
                    children: [{
                        id: 7,
                        label: '二级 3-1'
                    }, {
                        id: 8,
                        label: '二级 3-2'
                    }]
                }],
                defaultProps:{
                    children: 'children',
                    label: 'label'
                },
                form: {
                    name: '',
                    id: '',
                },
                formLabelWidth: '80px',
                timer:null,
                dialog: false,
                loading: false,
                selected_node:null,
            }
        },
        methods:{
            append(data) {
                this.dialog=true;
                this.selected_node=data;
            },

            remove(node, data) {
                const parent = node.parent;
                const children = parent.data.children || parent.data;
                const index = children.findIndex(d => d.id === data.id);
                children.splice(index, 1);
            },
            handleClose(done) {
                if (this.loading) {
                    return;
                }
                var _this=this;
                this.$confirm('确定要提交表单吗？')
                    .then(_ => {
                        this.loading = true;
                        this.timer = setTimeout(() => {
                            done();
                            // 动画关闭需要一定的时间
                            setTimeout(() => {
                                this.loading = false;

                                const newChild = { id: _this.form.id, label: _this.form.name, children: [] };
                                if (!_this.selected_node.children) {
                                    this.$set(_this.selected_node, 'children', []);
                                }
                                _this.selected_node.children.push(newChild);
                            }, 400);
                        }, 2000);
                    })
                    .catch(_ => {});
            },
            cancelForm() {
                this.loading = false;
                this.dialog = false;
                clearTimeout(this.timer);
            }

        }
    }
</script>

<style scoped>

</style>