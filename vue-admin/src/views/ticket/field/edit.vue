<template>
    <div style="border: 1px solid;">
        <h1>场地编辑</h1>
        <ul class="list" id="seats_list" style="border: 1px solid #0f0;"
            @mousedown="mousedown"
            @mousemove="mousemove"
            @mouseup="mouseup"
        >
            <li @click="click">1</li>
            <li @click="click">2</li>
            <li @click="click">3</li>
            <li @click="click">4</li>
            <li @click="click">5</li>
            <li @click="click">6</li>
            <li @click="click">7</li>
            <li @click="click">8</li>
            <li @click="click">9</li>
            <li @click="click">10</li>
            <li @click="click">11</li>
            <li>12</li>
            <li>13</li>
            <li>14</li>
            <li>15</li>
            <li>16</li>
            <li>17</li>
            <li>18</li>
            <li>19</li>
            <li>20</li>
            <li>21</li>
            <li>22</li>
            <!-- 鼠标拖拽出的遮罩 （定位为  position:absolute）-->
            <!-- 遮罩最好是在绑定了mouseover事件的元素内部，并且不要阻止遮罩的冒泡事件。这样鼠标移到了遮罩上面，依然可以利用冒泡执行父元素的mouseover事件，就不会出现遮罩只能扩大，不能缩小的情况了（亲自试过） -->
            <div id="moveSelected"></div>
        </ul>
    </div>

</template>

<script>
    export default {
        name: "ticket.field.edit",
        data(){
            return {
                pullStartLeft:0,//鼠标开始框选的left，top
                pullStartTop:0,
                pullFlag:false,//是否开始框选
                moveSelected:null,
                selectedList:[],
            }
        },
        computed:{

        },
        created(){

        },
        methods:{
            mousedown(event){
                this.moveSelected=document.getElementById('moveSelected');
                console.log('down',event)
                console.log('selector',this.moveSelected)
                this.pullFlag=true;
                this.clickFlag=true;
                this.moveSelected.style.top=event.layerY+"px";
                this.moveSelected.style.left=event.layerX+"px";
                this.pullStartLeft=event.layerX;
                this.pullStartTop=event.layerY;
                event.preventDefault();
                event.stopPropagation();
            },
            mousemove(event){
                if(!this.pullFlag)return;
                if(event.layerX<this.pullStartLeft){//向左拖动
                    this.moveSelected.style.left=event.layerX+"px";
                    this.moveSelected.style.width=(this.pullStartLeft-event.layerX)+"px";
                }
                else{//向右拖动
                    this.moveSelected.style.width=(event.layerX-this.pullStartLeft)+"px";
                }
                if(event.layerY<this.pullStartTop) {//向上
                    this.moveSelected.style.top=event.layerY+'px';
                    this.moveSelected.style.height=(this.pullStartTop-event.layerY)+"px";
                }
                else{//向下
                    this.moveSelected.style.height=(event.layerY-this.pullStartTop)+"px";
                }

                // console.log('move',event)
                event.preventDefault();
                event.stopPropagation();
            },
            mouseup(event){
                // console.log('up');
                this.moveSelected.style.bottom=Number(this.moveSelected.style.top.split('px')[0])+Number(this.moveSelected.style.height.split('px')[0]) + 'px';
                this.moveSelected.style.right=Number(this.moveSelected.style.left.split('px')[0])+Number(this.moveSelected.style.width.split('px')[0])+'px';

                this.findSelected();
                this.pullFlag=false;
                this.clearDragData();
                event.preventDefault();  // 阻止默认行为
                event.stopPropagation(); // 阻止事件冒泡
            },
            click(e){
                if(e.target.className==='selected'){
                    e.target.className=''
                }else{
                    e.target.className='selected'
                }
            },
            findSelected(){
                let blockList=document.getElementById('seats_list').getElementsByTagName("li")
                console.log('blocklist',blockList)
                for(let i=0;i<blockList.length;i++){
                    //计算每个块的定位信息
                    console.log(blockList[i].offsetLeft)
                    let left=blockList[i].offsetLeft;
                    let right=blockList[i].width+left;
                    let top=blockList[i].offsetTop;
                    let bottom=blockList[i].height+top;
                    // console.log(left,right,top,bottom)
                    //判断每个块是否被遮罩盖住（即选中）
                    let leftFlag=this.moveSelected.style.left.split('px')[0]<=left && left<=this.moveSelected.style.right.split('px')[0];
                    let rightFlag=this.moveSelected.style.left.split('px')[0]<=right && right<=this.moveSelected.style.right.split('px')[0];
                    let topFlag=this.moveSelected.style.top.split('px')[0]<=top && top<=this.moveSelected.style.bottom.split('px')[0];
                    let bottomFlag=this.moveSelected.style.top.split('px')[0]<=bottom && bottom<=this.moveSelected.style.bottom.split('px')[0];
                    console.log(leftFlag,rightFlag,topFlag,bottomFlag)
                    if((leftFlag || rightFlag) && (topFlag || bottomFlag)){
                        // if(blockList[i].className==='selected'){
                        //     blockList[i].className='';
                        // }else{
                        //     blockList[i].className='selected';
                        // }
                        blockList[i].className='selected';
                        this.selectedList.push(blockList[i]);
                    }
                }
                console.log(this.selectedList);
            },
            clearDragData(){
                this.moveSelected.style.width=0;
                this.moveSelected.style.height=0;
                this.moveSelected.style.top=0;
                this.moveSelected.style.left=0;
                this.moveSelected.style.bottom=0;
                this.moveSelected.style.right=0;
            }
        }
    }
</script>

<style scoped>
    ul{
        width:500px;
        height:auto;
        margin:0;
        padding:20px;
        font-size: 0;
        /*需设置定位*/
        position:relative;
    }
    li{
        width:70px;
        height:70px;
        margin:10px;
        padding:0;
        display:inline-block;
        vertical-align: top;
        font-size: 13px;
        border:1px solid #d9d9d9;
    }
    #moveSelected{
        position:absolute;
        background-color: blue;
        opacity:0.3;
        border:1px dashed #d9d9d9;
        top:0;
        left:0;
    }
    .selected{
        background-color: pink;
    }
</style>