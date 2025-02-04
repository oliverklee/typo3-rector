<T3DataStructure>
    <sheets>
        <sDEF>
            <ROOT>
                <sheetTitle>sheetTitle</sheetTitle>
                <type>array</type>
                <el>
                    <aColumn>
                        <config>
                            <type>input</type>
                            <eval>email,trim,unique</eval>
                            <required>1</required>
                        </config>
                    </aColumn>
                    <differentColumn>
                        <config>
                            <type>input</type>
                            <eval>trim,unique</eval>
                        </config>
                    </differentColumn>
                    <wrongTypeColumn>
                        <config>
                            <type>text</type>
                            <eval>email,trim,unique</eval>
                        </config>
                    </wrongTypeColumn>
                    <alreadyMigratedColumn>
                        <config>
                            <type>email</type>
                        </config>
                    </alreadyMigratedColumn>
                </el>
            </ROOT>
        </sDEF>
    </sheets>
</T3DataStructure>
-----
<T3DataStructure>
    <sheets>
        <sDEF>
            <ROOT>
                <sheetTitle>sheetTitle</sheetTitle>
                <type>array</type>
                <el>
                    <aColumn>
                        <config>
                            <type>email</type>
                            <eval>unique</eval>
                            <required>1</required>
                        </config>
                    </aColumn>
                    <differentColumn>
                        <config>
                            <type>input</type>
                            <eval>trim,unique</eval>
                        </config>
                    </differentColumn>
                    <wrongTypeColumn>
                        <config>
                            <type>text</type>
                            <eval>email,trim,unique</eval>
                        </config>
                    </wrongTypeColumn>
                    <alreadyMigratedColumn>
                        <config>
                            <type>email</type>
                        </config>
                    </alreadyMigratedColumn>
                </el>
            </ROOT>
        </sDEF>
    </sheets>
</T3DataStructure>
