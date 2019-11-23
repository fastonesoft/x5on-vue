### iview
数值型表单控件要标上type="number"

##### style
// marginRight: this.gutter / -2 + 'px'

      <Card title="任务流程" style="height:160px;">
        <Tag color="geekblue" slot="extra">程</Tag>
        <Steps :current="0">
          <Step title="任务分配" content="案件测算工作分配"></Step>
          <Step title="税费测算" content="接受任务进行测算"></Step>
          <Step title="测算复核" content="测算结果二次复核"></Step>
          <Step title="案件审批" content="案件集体审议记录"></Step>
          <Step title="文书制作" content="出具涉税处理意见"></Step>
        </Steps>
      </Card>

### Vue 
'vue-cli-service' 不是内部或外部命令，也不是可运行的程序
npm install --global vue-cli