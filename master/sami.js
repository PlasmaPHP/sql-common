
window.projectVersion = 'master';

(function(root) {

    var bhIndex = null;
    var rootPath = '';
    var treeHtml = '        <ul>                <li data-name="namespace:Plasma" class="opened">                    <div style="padding-left:0px" class="hd">                        <span class="glyphicon glyphicon-play"></span><a href="Plasma.html">Plasma</a>                    </div>                    <div class="bd">                                <ul>                <li data-name="namespace:Plasma_SQL" class="opened">                    <div style="padding-left:18px" class="hd">                        <span class="glyphicon glyphicon-play"></span><a href="Plasma/SQL.html">SQL</a>                    </div>                    <div class="bd">                                <ul>                <li data-name="namespace:Plasma_SQL_Grammar" >                    <div style="padding-left:36px" class="hd">                        <span class="glyphicon glyphicon-play"></span><a href="Plasma/SQL/Grammar.html">Grammar</a>                    </div>                    <div class="bd">                                <ul>                <li data-name="class:Plasma_SQL_Grammar_MySQL" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="Plasma/SQL/Grammar/MySQL.html">MySQL</a>                    </div>                </li>                            <li data-name="class:Plasma_SQL_Grammar_PostgreSQL" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="Plasma/SQL/Grammar/PostgreSQL.html">PostgreSQL</a>                    </div>                </li>                </ul></div>                </li>                            <li data-name="namespace:Plasma_SQL_QueryExpressions" >                    <div style="padding-left:36px" class="hd">                        <span class="glyphicon glyphicon-play"></span><a href="Plasma/SQL/QueryExpressions.html">QueryExpressions</a>                    </div>                    <div class="bd">                                <ul>                <li data-name="class:Plasma_SQL_QueryExpressions_BetweenParameter" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="Plasma/SQL/QueryExpressions/BetweenParameter.html">BetweenParameter</a>                    </div>                </li>                            <li data-name="class:Plasma_SQL_QueryExpressions_Column" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="Plasma/SQL/QueryExpressions/Column.html">Column</a>                    </div>                </li>                            <li data-name="class:Plasma_SQL_QueryExpressions_Constraint" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="Plasma/SQL/QueryExpressions/Constraint.html">Constraint</a>                    </div>                </li>                            <li data-name="class:Plasma_SQL_QueryExpressions_Fragment" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="Plasma/SQL/QueryExpressions/Fragment.html">Fragment</a>                    </div>                </li>                            <li data-name="class:Plasma_SQL_QueryExpressions_FragmentedWhere" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="Plasma/SQL/QueryExpressions/FragmentedWhere.html">FragmentedWhere</a>                    </div>                </li>                            <li data-name="class:Plasma_SQL_QueryExpressions_GroupBy" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="Plasma/SQL/QueryExpressions/GroupBy.html">GroupBy</a>                    </div>                </li>                            <li data-name="class:Plasma_SQL_QueryExpressions_Join" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="Plasma/SQL/QueryExpressions/Join.html">Join</a>                    </div>                </li>                            <li data-name="class:Plasma_SQL_QueryExpressions_On" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="Plasma/SQL/QueryExpressions/On.html">On</a>                    </div>                </li>                            <li data-name="class:Plasma_SQL_QueryExpressions_OrderBy" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="Plasma/SQL/QueryExpressions/OrderBy.html">OrderBy</a>                    </div>                </li>                            <li data-name="class:Plasma_SQL_QueryExpressions_Parameter" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="Plasma/SQL/QueryExpressions/Parameter.html">Parameter</a>                    </div>                </li>                            <li data-name="class:Plasma_SQL_QueryExpressions_Subquery" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="Plasma/SQL/QueryExpressions/Subquery.html">Subquery</a>                    </div>                </li>                            <li data-name="class:Plasma_SQL_QueryExpressions_Table" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="Plasma/SQL/QueryExpressions/Table.html">Table</a>                    </div>                </li>                            <li data-name="class:Plasma_SQL_QueryExpressions_Union" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="Plasma/SQL/QueryExpressions/Union.html">Union</a>                    </div>                </li>                            <li data-name="class:Plasma_SQL_QueryExpressions_UnionAll" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="Plasma/SQL/QueryExpressions/UnionAll.html">UnionAll</a>                    </div>                </li>                            <li data-name="class:Plasma_SQL_QueryExpressions_Where" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="Plasma/SQL/QueryExpressions/Where.html">Where</a>                    </div>                </li>                            <li data-name="class:Plasma_SQL_QueryExpressions_WhereBuilder" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="Plasma/SQL/QueryExpressions/WhereBuilder.html">WhereBuilder</a>                    </div>                </li>                </ul></div>                </li>                            <li data-name="class:Plasma_SQL_ConflictResolution" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="Plasma/SQL/ConflictResolution.html">ConflictResolution</a>                    </div>                </li>                            <li data-name="class:Plasma_SQL_GrammarInterface" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="Plasma/SQL/GrammarInterface.html">GrammarInterface</a>                    </div>                </li>                            <li data-name="class:Plasma_SQL_OnConflict" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="Plasma/SQL/OnConflict.html">OnConflict</a>                    </div>                </li>                            <li data-name="class:Plasma_SQL_QueryBuilder" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="Plasma/SQL/QueryBuilder.html">QueryBuilder</a>                    </div>                </li>                            <li data-name="class:Plasma_SQL_WhereBuilder" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="Plasma/SQL/WhereBuilder.html">WhereBuilder</a>                    </div>                </li>                </ul></div>                </li>                </ul></div>                </li>                </ul>';

    var searchTypeClasses = {
        'Namespace': 'label-default',
        'Class': 'label-info',
        'Interface': 'label-primary',
        'Trait': 'label-success',
        'Method': 'label-danger',
        '_': 'label-warning'
    };

    var searchIndex = [
                    
            {"type": "Namespace", "link": "Plasma.html", "name": "Plasma", "doc": "Namespace Plasma"},{"type": "Namespace", "link": "Plasma/SQL.html", "name": "Plasma\\SQL", "doc": "Namespace Plasma\\SQL"},{"type": "Namespace", "link": "Plasma/SQL/Grammar.html", "name": "Plasma\\SQL\\Grammar", "doc": "Namespace Plasma\\SQL\\Grammar"},{"type": "Namespace", "link": "Plasma/SQL/QueryExpressions.html", "name": "Plasma\\SQL\\QueryExpressions", "doc": "Namespace Plasma\\SQL\\QueryExpressions"},
            {"type": "Interface", "fromName": "Plasma\\SQL", "fromLink": "Plasma/SQL.html", "link": "Plasma/SQL/GrammarInterface.html", "name": "Plasma\\SQL\\GrammarInterface", "doc": "&quot;Grammar describe a SQL flavour, most notably MySQL vs. PostgreSQL.&quot;"},
                                                        {"type": "Method", "fromName": "Plasma\\SQL\\GrammarInterface", "fromLink": "Plasma/SQL/GrammarInterface.html", "link": "Plasma/SQL/GrammarInterface.html#method_quoteTable", "name": "Plasma\\SQL\\GrammarInterface::quoteTable", "doc": "&quot;Wraps the table name into quotes.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\GrammarInterface", "fromLink": "Plasma/SQL/GrammarInterface.html", "link": "Plasma/SQL/GrammarInterface.html#method_quoteColumn", "name": "Plasma\\SQL\\GrammarInterface::quoteColumn", "doc": "&quot;Wraps the column name into quotes.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\GrammarInterface", "fromLink": "Plasma/SQL/GrammarInterface.html", "link": "Plasma/SQL/GrammarInterface.html#method_onConflictToSQL", "name": "Plasma\\SQL\\GrammarInterface::onConflictToSQL", "doc": "&quot;Converts an ON CONFLICT resolution to the equivalent DBMS-specific SQL string.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\GrammarInterface", "fromLink": "Plasma/SQL/GrammarInterface.html", "link": "Plasma/SQL/GrammarInterface.html#method_supportsRowLocking", "name": "Plasma\\SQL\\GrammarInterface::supportsRowLocking", "doc": "&quot;Whether the grammar supports row-level locking.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\GrammarInterface", "fromLink": "Plasma/SQL/GrammarInterface.html", "link": "Plasma/SQL/GrammarInterface.html#method_getSQLForRowLocking", "name": "Plasma\\SQL\\GrammarInterface::getSQLForRowLocking", "doc": "&quot;Get the SQL command for the given row-level locking mode.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\GrammarInterface", "fromLink": "Plasma/SQL/GrammarInterface.html", "link": "Plasma/SQL/GrammarInterface.html#method_supportsReturning", "name": "Plasma\\SQL\\GrammarInterface::supportsReturning", "doc": "&quot;Whether the grammar supports RETURNING.&quot;"},
            
            
            {"type": "Class", "fromName": "Plasma\\SQL", "fromLink": "Plasma/SQL.html", "link": "Plasma/SQL/ConflictResolution.html", "name": "Plasma\\SQL\\ConflictResolution", "doc": "&quot;Used to describe which keyword is used for the insert and what is appended to the SQL query.&quot;"},
                                                        {"type": "Method", "fromName": "Plasma\\SQL\\ConflictResolution", "fromLink": "Plasma/SQL/ConflictResolution.html", "link": "Plasma/SQL/ConflictResolution.html#method___construct", "name": "Plasma\\SQL\\ConflictResolution::__construct", "doc": "&quot;Constructor.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\ConflictResolution", "fromLink": "Plasma/SQL/ConflictResolution.html", "link": "Plasma/SQL/ConflictResolution.html#method_getKeyword", "name": "Plasma\\SQL\\ConflictResolution::getKeyword", "doc": "&quot;Get the SQL keyword for the insert.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\ConflictResolution", "fromLink": "Plasma/SQL/ConflictResolution.html", "link": "Plasma/SQL/ConflictResolution.html#method_getAppendum", "name": "Plasma\\SQL\\ConflictResolution::getAppendum", "doc": "&quot;Get the string to append to the SQL query.&quot;"},
            {"type": "Class", "fromName": "Plasma\\SQL\\Grammar", "fromLink": "Plasma/SQL/Grammar.html", "link": "Plasma/SQL/Grammar/MySQL.html", "name": "Plasma\\SQL\\Grammar\\MySQL", "doc": "&quot;MySQL Grammar.&quot;"},
                                                        {"type": "Method", "fromName": "Plasma\\SQL\\Grammar\\MySQL", "fromLink": "Plasma/SQL/Grammar/MySQL.html", "link": "Plasma/SQL/Grammar/MySQL.html#method_quoteTable", "name": "Plasma\\SQL\\Grammar\\MySQL::quoteTable", "doc": "&quot;Wraps the table name into quotes.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\Grammar\\MySQL", "fromLink": "Plasma/SQL/Grammar/MySQL.html", "link": "Plasma/SQL/Grammar/MySQL.html#method_quoteColumn", "name": "Plasma\\SQL\\Grammar\\MySQL::quoteColumn", "doc": "&quot;Wraps the column name into quotes.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\Grammar\\MySQL", "fromLink": "Plasma/SQL/Grammar/MySQL.html", "link": "Plasma/SQL/Grammar/MySQL.html#method_onConflictToSQL", "name": "Plasma\\SQL\\Grammar\\MySQL::onConflictToSQL", "doc": "&quot;Converts an ON CONFLICT resolution to the equivalent DBMS-specific SQL string.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\Grammar\\MySQL", "fromLink": "Plasma/SQL/Grammar/MySQL.html", "link": "Plasma/SQL/Grammar/MySQL.html#method_supportsRowLocking", "name": "Plasma\\SQL\\Grammar\\MySQL::supportsRowLocking", "doc": "&quot;Whether the grammar supports row-level locking.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\Grammar\\MySQL", "fromLink": "Plasma/SQL/Grammar/MySQL.html", "link": "Plasma/SQL/Grammar/MySQL.html#method_getSQLForRowLocking", "name": "Plasma\\SQL\\Grammar\\MySQL::getSQLForRowLocking", "doc": "&quot;Get the SQL command for the given row-level locking mode.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\Grammar\\MySQL", "fromLink": "Plasma/SQL/Grammar/MySQL.html", "link": "Plasma/SQL/Grammar/MySQL.html#method_supportsReturning", "name": "Plasma\\SQL\\Grammar\\MySQL::supportsReturning", "doc": "&quot;Whether the grammar supports RETURNING.&quot;"},
            {"type": "Class", "fromName": "Plasma\\SQL\\Grammar", "fromLink": "Plasma/SQL/Grammar.html", "link": "Plasma/SQL/Grammar/PostgreSQL.html", "name": "Plasma\\SQL\\Grammar\\PostgreSQL", "doc": "&quot;PostgreSQL Grammar.&quot;"},
                                                        {"type": "Method", "fromName": "Plasma\\SQL\\Grammar\\PostgreSQL", "fromLink": "Plasma/SQL/Grammar/PostgreSQL.html", "link": "Plasma/SQL/Grammar/PostgreSQL.html#method_quoteTable", "name": "Plasma\\SQL\\Grammar\\PostgreSQL::quoteTable", "doc": "&quot;Wraps the table name into quotes.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\Grammar\\PostgreSQL", "fromLink": "Plasma/SQL/Grammar/PostgreSQL.html", "link": "Plasma/SQL/Grammar/PostgreSQL.html#method_quoteColumn", "name": "Plasma\\SQL\\Grammar\\PostgreSQL::quoteColumn", "doc": "&quot;Wraps the column name into quotes.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\Grammar\\PostgreSQL", "fromLink": "Plasma/SQL/Grammar/PostgreSQL.html", "link": "Plasma/SQL/Grammar/PostgreSQL.html#method_onConflictToSQL", "name": "Plasma\\SQL\\Grammar\\PostgreSQL::onConflictToSQL", "doc": "&quot;Converts an ON CONFLICT resolution to the equivalent DBMS-specific SQL string.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\Grammar\\PostgreSQL", "fromLink": "Plasma/SQL/Grammar/PostgreSQL.html", "link": "Plasma/SQL/Grammar/PostgreSQL.html#method_supportsRowLocking", "name": "Plasma\\SQL\\Grammar\\PostgreSQL::supportsRowLocking", "doc": "&quot;Whether the grammar supports row-level locking.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\Grammar\\PostgreSQL", "fromLink": "Plasma/SQL/Grammar/PostgreSQL.html", "link": "Plasma/SQL/Grammar/PostgreSQL.html#method_getSQLForRowLocking", "name": "Plasma\\SQL\\Grammar\\PostgreSQL::getSQLForRowLocking", "doc": "&quot;Get the SQL command for the given row-level locking mode.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\Grammar\\PostgreSQL", "fromLink": "Plasma/SQL/Grammar/PostgreSQL.html", "link": "Plasma/SQL/Grammar/PostgreSQL.html#method_supportsReturning", "name": "Plasma\\SQL\\Grammar\\PostgreSQL::supportsReturning", "doc": "&quot;Whether the grammar supports RETURNING.&quot;"},
            {"type": "Class", "fromName": "Plasma\\SQL", "fromLink": "Plasma/SQL.html", "link": "Plasma/SQL/OnConflict.html", "name": "Plasma\\SQL\\OnConflict", "doc": "&quot;Represents an ON CONFLICT resolution.&quot;"},
                                                        {"type": "Method", "fromName": "Plasma\\SQL\\OnConflict", "fromLink": "Plasma/SQL/OnConflict.html", "link": "Plasma/SQL/OnConflict.html#method___construct", "name": "Plasma\\SQL\\OnConflict::__construct", "doc": "&quot;Constructor.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\OnConflict", "fromLink": "Plasma/SQL/OnConflict.html", "link": "Plasma/SQL/OnConflict.html#method_getType", "name": "Plasma\\SQL\\OnConflict::getType", "doc": "&quot;Get the conflict resolution type.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\OnConflict", "fromLink": "Plasma/SQL/OnConflict.html", "link": "Plasma/SQL/OnConflict.html#method_getReplaceColumns", "name": "Plasma\\SQL\\OnConflict::getReplaceColumns", "doc": "&quot;Get the columns to replace.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\OnConflict", "fromLink": "Plasma/SQL/OnConflict.html", "link": "Plasma/SQL/OnConflict.html#method_addReplaceColumn", "name": "Plasma\\SQL\\OnConflict::addReplaceColumn", "doc": "&quot;Adds a column to replace with the new value on conflict.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\OnConflict", "fromLink": "Plasma/SQL/OnConflict.html", "link": "Plasma/SQL/OnConflict.html#method_getConflictTargets", "name": "Plasma\\SQL\\OnConflict::getConflictTargets", "doc": "&quot;Get the conflict targets.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\OnConflict", "fromLink": "Plasma/SQL/OnConflict.html", "link": "Plasma/SQL/OnConflict.html#method_addConflictTarget", "name": "Plasma\\SQL\\OnConflict::addConflictTarget", "doc": "&quot;Add a conflict target.&quot;"},
            {"type": "Class", "fromName": "Plasma\\SQL", "fromLink": "Plasma/SQL.html", "link": "Plasma/SQL/QueryBuilder.html", "name": "Plasma\\SQL\\QueryBuilder", "doc": "&quot;Provides an implementation for a SQL querybuilder.&quot;"},
                                                        {"type": "Method", "fromName": "Plasma\\SQL\\QueryBuilder", "fromLink": "Plasma/SQL/QueryBuilder.html", "link": "Plasma/SQL/QueryBuilder.html#method_create", "name": "Plasma\\SQL\\QueryBuilder::create", "doc": "&quot;Creates a new instance of the querybuilder.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\QueryBuilder", "fromLink": "Plasma/SQL/QueryBuilder.html", "link": "Plasma/SQL/QueryBuilder.html#method_createWithGrammar", "name": "Plasma\\SQL\\QueryBuilder::createWithGrammar", "doc": "&quot;Creates a new instance of the querybuilder with a grammar.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\QueryBuilder", "fromLink": "Plasma/SQL/QueryBuilder.html", "link": "Plasma/SQL/QueryBuilder.html#method_between", "name": "Plasma\\SQL\\QueryBuilder::between", "doc": "&quot;Creates a new BetweenParameter for the two between values.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\QueryBuilder", "fromLink": "Plasma/SQL/QueryBuilder.html", "link": "Plasma/SQL/QueryBuilder.html#method_column", "name": "Plasma\\SQL\\QueryBuilder::column", "doc": "&quot;Creates a new Column.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\QueryBuilder", "fromLink": "Plasma/SQL/QueryBuilder.html", "link": "Plasma/SQL/QueryBuilder.html#method_fragment", "name": "Plasma\\SQL\\QueryBuilder::fragment", "doc": "&quot;Creates a new Fragment. All placeholders &lt;code&gt;?&lt;\/code&gt; in the operation string will be replaced by the following arguments.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\QueryBuilder", "fromLink": "Plasma/SQL/QueryBuilder.html", "link": "Plasma/SQL/QueryBuilder.html#method_withGrammar", "name": "Plasma\\SQL\\QueryBuilder::withGrammar", "doc": "&quot;Clones the querybuilder and sets the grammar.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\QueryBuilder", "fromLink": "Plasma/SQL/QueryBuilder.html", "link": "Plasma/SQL/QueryBuilder.html#method_from", "name": "Plasma\\SQL\\QueryBuilder::from", "doc": "&quot;Sets the target table to the given table.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\QueryBuilder", "fromLink": "Plasma/SQL/QueryBuilder.html", "link": "Plasma/SQL/QueryBuilder.html#method_into", "name": "Plasma\\SQL\\QueryBuilder::into", "doc": "&quot;Sets the target table to the given table. Alias for &lt;code&gt;from&lt;\/code&gt;.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\QueryBuilder", "fromLink": "Plasma/SQL/QueryBuilder.html", "link": "Plasma/SQL/QueryBuilder.html#method_distinct", "name": "Plasma\\SQL\\QueryBuilder::distinct", "doc": "&quot;Adds a DISTINCT flag to this query.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\QueryBuilder", "fromLink": "Plasma/SQL/QueryBuilder.html", "link": "Plasma/SQL/QueryBuilder.html#method_returning", "name": "Plasma\\SQL\\QueryBuilder::returning", "doc": "&quot;Adds a RETURNING flag to this query.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\QueryBuilder", "fromLink": "Plasma/SQL/QueryBuilder.html", "link": "Plasma/SQL/QueryBuilder.html#method_setSelectRowLocking", "name": "Plasma\\SQL\\QueryBuilder::setSelectRowLocking", "doc": "&quot;Sets the SELECT row-level locking.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\QueryBuilder", "fromLink": "Plasma/SQL/QueryBuilder.html", "link": "Plasma/SQL/QueryBuilder.html#method_select", "name": "Plasma\\SQL\\QueryBuilder::select", "doc": "&quot;Select columns with an optional column alias (as the key).&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\QueryBuilder", "fromLink": "Plasma/SQL/QueryBuilder.html", "link": "Plasma/SQL/QueryBuilder.html#method_insert", "name": "Plasma\\SQL\\QueryBuilder::insert", "doc": "&quot;Insert a row.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\QueryBuilder", "fromLink": "Plasma/SQL/QueryBuilder.html", "link": "Plasma/SQL/QueryBuilder.html#method_insertWithSubquery", "name": "Plasma\\SQL\\QueryBuilder::insertWithSubquery", "doc": "&quot;Inserts a row using a subquery.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\QueryBuilder", "fromLink": "Plasma/SQL/QueryBuilder.html", "link": "Plasma/SQL/QueryBuilder.html#method_update", "name": "Plasma\\SQL\\QueryBuilder::update", "doc": "&quot;Updates the rows passing the selection.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\QueryBuilder", "fromLink": "Plasma/SQL/QueryBuilder.html", "link": "Plasma/SQL/QueryBuilder.html#method_delete", "name": "Plasma\\SQL\\QueryBuilder::delete", "doc": "&quot;Deletes rows passing the selection.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\QueryBuilder", "fromLink": "Plasma/SQL/QueryBuilder.html", "link": "Plasma/SQL/QueryBuilder.html#method_join", "name": "Plasma\\SQL\\QueryBuilder::join", "doc": "&quot;Adds a JOIN query with the table and optional alias.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\QueryBuilder", "fromLink": "Plasma/SQL/QueryBuilder.html", "link": "Plasma/SQL/QueryBuilder.html#method_innerJoin", "name": "Plasma\\SQL\\QueryBuilder::innerJoin", "doc": "&quot;Adds a INNER JOIN query with the table and optional alias.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\QueryBuilder", "fromLink": "Plasma/SQL/QueryBuilder.html", "link": "Plasma/SQL/QueryBuilder.html#method_outerJoin", "name": "Plasma\\SQL\\QueryBuilder::outerJoin", "doc": "&quot;Adds a OUTER JOIN query with the table and optional alias.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\QueryBuilder", "fromLink": "Plasma/SQL/QueryBuilder.html", "link": "Plasma/SQL/QueryBuilder.html#method_leftJoin", "name": "Plasma\\SQL\\QueryBuilder::leftJoin", "doc": "&quot;Adds a JOIN query with the table and optional alias.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\QueryBuilder", "fromLink": "Plasma/SQL/QueryBuilder.html", "link": "Plasma/SQL/QueryBuilder.html#method_rightJoin", "name": "Plasma\\SQL\\QueryBuilder::rightJoin", "doc": "&quot;Adds a RIGHT JOIN query with the table and optional alias.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\QueryBuilder", "fromLink": "Plasma/SQL/QueryBuilder.html", "link": "Plasma/SQL/QueryBuilder.html#method_on", "name": "Plasma\\SQL\\QueryBuilder::on", "doc": "&quot;Adds an &lt;code&gt;ON&lt;\/code&gt; expression to the last &lt;code&gt;JOIN&lt;\/code&gt; expression.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\QueryBuilder", "fromLink": "Plasma/SQL/QueryBuilder.html", "link": "Plasma/SQL/QueryBuilder.html#method_where", "name": "Plasma\\SQL\\QueryBuilder::where", "doc": "&quot;Put the previous WHERE clausel with a logical AND constraint to this WHERE clausel.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\QueryBuilder", "fromLink": "Plasma/SQL/QueryBuilder.html", "link": "Plasma/SQL/QueryBuilder.html#method_orWhere", "name": "Plasma\\SQL\\QueryBuilder::orWhere", "doc": "&quot;Put the previous WHERE clausel with a logical OR constraint to this WHERE clausel.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\QueryBuilder", "fromLink": "Plasma/SQL/QueryBuilder.html", "link": "Plasma/SQL/QueryBuilder.html#method_whereExt", "name": "Plasma\\SQL\\QueryBuilder::whereExt", "doc": "&quot;Extended where building. The callback gets a &lt;code&gt;WhereBuilder&lt;\/code&gt; instance, where the callback is supposed to build the WHERE clausel.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\QueryBuilder", "fromLink": "Plasma/SQL/QueryBuilder.html", "link": "Plasma/SQL/QueryBuilder.html#method_orWhereExt", "name": "Plasma\\SQL\\QueryBuilder::orWhereExt", "doc": "&quot;Extended where building. The callback gets a &lt;code&gt;WhereBuilder&lt;\/code&gt; instance, where the callback is supposed to build the WHERE clausel.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\QueryBuilder", "fromLink": "Plasma/SQL/QueryBuilder.html", "link": "Plasma/SQL/QueryBuilder.html#method_whereFragment", "name": "Plasma\\SQL\\QueryBuilder::whereFragment", "doc": "&quot;Put the previous WHERE clausel with a logical AND constraint to this fragmented WHERE clausel.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\QueryBuilder", "fromLink": "Plasma/SQL/QueryBuilder.html", "link": "Plasma/SQL/QueryBuilder.html#method_orWhereFragment", "name": "Plasma\\SQL\\QueryBuilder::orWhereFragment", "doc": "&quot;Put the previous WHERE clausel with a logical OR constraint to this fragmented WHERE clausel.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\QueryBuilder", "fromLink": "Plasma/SQL/QueryBuilder.html", "link": "Plasma/SQL/QueryBuilder.html#method_having", "name": "Plasma\\SQL\\QueryBuilder::having", "doc": "&quot;Put the previous HAVING clausel with a logical AND constraint to this HAVING clausel.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\QueryBuilder", "fromLink": "Plasma/SQL/QueryBuilder.html", "link": "Plasma/SQL/QueryBuilder.html#method_orHaving", "name": "Plasma\\SQL\\QueryBuilder::orHaving", "doc": "&quot;Put the previous HAVING clausel with a logical OR constraint to this HAVING clausel.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\QueryBuilder", "fromLink": "Plasma/SQL/QueryBuilder.html", "link": "Plasma/SQL/QueryBuilder.html#method_havingExt", "name": "Plasma\\SQL\\QueryBuilder::havingExt", "doc": "&quot;Extended having building. The callback gets a &lt;code&gt;WhereBuilder&lt;\/code&gt; instance, where the callback is supposed to build the HAVING clausel.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\QueryBuilder", "fromLink": "Plasma/SQL/QueryBuilder.html", "link": "Plasma/SQL/QueryBuilder.html#method_orHavingExt", "name": "Plasma\\SQL\\QueryBuilder::orHavingExt", "doc": "&quot;Extended having building. The callback gets a &lt;code&gt;WhereBuilder&lt;\/code&gt; instance, where the callback is supposed to build the HAVING clausel.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\QueryBuilder", "fromLink": "Plasma/SQL/QueryBuilder.html", "link": "Plasma/SQL/QueryBuilder.html#method_havingFragment", "name": "Plasma\\SQL\\QueryBuilder::havingFragment", "doc": "&quot;Put the previous HAVING clausel with a logical AND constraint to this fragmented HAVING clausel.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\QueryBuilder", "fromLink": "Plasma/SQL/QueryBuilder.html", "link": "Plasma/SQL/QueryBuilder.html#method_orHavingFragment", "name": "Plasma\\SQL\\QueryBuilder::orHavingFragment", "doc": "&quot;Put the previous HAVING clausel with a logical OR constraint to this fragmented HAVING clausel.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\QueryBuilder", "fromLink": "Plasma/SQL/QueryBuilder.html", "link": "Plasma/SQL/QueryBuilder.html#method_limit", "name": "Plasma\\SQL\\QueryBuilder::limit", "doc": "&quot;Set the limit for the &lt;code&gt;SELECT&lt;\/code&gt; query.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\QueryBuilder", "fromLink": "Plasma/SQL/QueryBuilder.html", "link": "Plasma/SQL/QueryBuilder.html#method_offset", "name": "Plasma\\SQL\\QueryBuilder::offset", "doc": "&quot;Set the offset for the &lt;code&gt;SELECT&lt;\/code&gt; query.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\QueryBuilder", "fromLink": "Plasma/SQL/QueryBuilder.html", "link": "Plasma/SQL/QueryBuilder.html#method_orderBy", "name": "Plasma\\SQL\\QueryBuilder::orderBy", "doc": "&quot;Add an &lt;code&gt;ORDER BY&lt;\/code&gt; to the query. This will aggregate.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\QueryBuilder", "fromLink": "Plasma/SQL/QueryBuilder.html", "link": "Plasma/SQL/QueryBuilder.html#method_groupBy", "name": "Plasma\\SQL\\QueryBuilder::groupBy", "doc": "&quot;Add an &lt;code&gt;GROUP BY&lt;\/code&gt; to the query. This will aggregate.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\QueryBuilder", "fromLink": "Plasma/SQL/QueryBuilder.html", "link": "Plasma/SQL/QueryBuilder.html#method_subquery", "name": "Plasma\\SQL\\QueryBuilder::subquery", "doc": "&quot;Adds a subquery to the &lt;code&gt;SELECT&lt;\/code&gt; query.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\QueryBuilder", "fromLink": "Plasma/SQL/QueryBuilder.html", "link": "Plasma/SQL/QueryBuilder.html#method_union", "name": "Plasma\\SQL\\QueryBuilder::union", "doc": "&quot;Adds an &lt;code&gt;UNION&lt;\/code&gt; to the &lt;code&gt;SELECT&lt;\/code&gt; query.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\QueryBuilder", "fromLink": "Plasma/SQL/QueryBuilder.html", "link": "Plasma/SQL/QueryBuilder.html#method_unionAll", "name": "Plasma\\SQL\\QueryBuilder::unionAll", "doc": "&quot;Adds an &lt;code&gt;UNION ALL&lt;\/code&gt; to the &lt;code&gt;SELECT&lt;\/code&gt; query.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\QueryBuilder", "fromLink": "Plasma/SQL/QueryBuilder.html", "link": "Plasma/SQL/QueryBuilder.html#method_setPrefix", "name": "Plasma\\SQL\\QueryBuilder::setPrefix", "doc": "&quot;Sets the prefix.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\QueryBuilder", "fromLink": "Plasma/SQL/QueryBuilder.html", "link": "Plasma/SQL/QueryBuilder.html#method_getQuery", "name": "Plasma\\SQL\\QueryBuilder::getQuery", "doc": "&quot;Returns the query.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\QueryBuilder", "fromLink": "Plasma/SQL/QueryBuilder.html", "link": "Plasma/SQL/QueryBuilder.html#method_getParameters", "name": "Plasma\\SQL\\QueryBuilder::getParameters", "doc": "&quot;Returns the associated parameters for the query.&quot;"},
            {"type": "Class", "fromName": "Plasma\\SQL\\QueryExpressions", "fromLink": "Plasma/SQL/QueryExpressions.html", "link": "Plasma/SQL/QueryExpressions/BetweenParameter.html", "name": "Plasma\\SQL\\QueryExpressions\\BetweenParameter", "doc": "&quot;Represents a parameter used for BETWEEN.&quot;"},
                                                        {"type": "Method", "fromName": "Plasma\\SQL\\QueryExpressions\\BetweenParameter", "fromLink": "Plasma/SQL/QueryExpressions/BetweenParameter.html", "link": "Plasma/SQL/QueryExpressions/BetweenParameter.html#method___construct", "name": "Plasma\\SQL\\QueryExpressions\\BetweenParameter::__construct", "doc": "&quot;Constructor.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\QueryExpressions\\BetweenParameter", "fromLink": "Plasma/SQL/QueryExpressions/BetweenParameter.html", "link": "Plasma/SQL/QueryExpressions/BetweenParameter.html#method_hasValue", "name": "Plasma\\SQL\\QueryExpressions\\BetweenParameter::hasValue", "doc": "&quot;Whether this parameter has a value. If not, the QueryBuilder is expected to throw an Exception.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\QueryExpressions\\BetweenParameter", "fromLink": "Plasma/SQL/QueryExpressions/BetweenParameter.html", "link": "Plasma/SQL/QueryExpressions/BetweenParameter.html#method_getValue", "name": "Plasma\\SQL\\QueryExpressions\\BetweenParameter::getValue", "doc": "&quot;Get the value.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\QueryExpressions\\BetweenParameter", "fromLink": "Plasma/SQL/QueryExpressions/BetweenParameter.html", "link": "Plasma/SQL/QueryExpressions/BetweenParameter.html#method_setValue", "name": "Plasma\\SQL\\QueryExpressions\\BetweenParameter::setValue", "doc": "&quot;Set the value.&quot;"},
            {"type": "Class", "fromName": "Plasma\\SQL\\QueryExpressions", "fromLink": "Plasma/SQL/QueryExpressions.html", "link": "Plasma/SQL/QueryExpressions/Column.html", "name": "Plasma\\SQL\\QueryExpressions\\Column", "doc": "&quot;Represents a column.&quot;"},
                                                        {"type": "Method", "fromName": "Plasma\\SQL\\QueryExpressions\\Column", "fromLink": "Plasma/SQL/QueryExpressions/Column.html", "link": "Plasma/SQL/QueryExpressions/Column.html#method___construct", "name": "Plasma\\SQL\\QueryExpressions\\Column::__construct", "doc": "&quot;Constructor.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\QueryExpressions\\Column", "fromLink": "Plasma/SQL/QueryExpressions/Column.html", "link": "Plasma/SQL/QueryExpressions/Column.html#method_getColumn", "name": "Plasma\\SQL\\QueryExpressions\\Column::getColumn", "doc": "&quot;Get the column.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\QueryExpressions\\Column", "fromLink": "Plasma/SQL/QueryExpressions/Column.html", "link": "Plasma/SQL/QueryExpressions/Column.html#method_getAlias", "name": "Plasma\\SQL\\QueryExpressions\\Column::getAlias", "doc": "&quot;Get the alias.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\QueryExpressions\\Column", "fromLink": "Plasma/SQL/QueryExpressions/Column.html", "link": "Plasma/SQL/QueryExpressions/Column.html#method_allowEscape", "name": "Plasma\\SQL\\QueryExpressions\\Column::allowEscape", "doc": "&quot;Whether the column allows escaping.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\QueryExpressions\\Column", "fromLink": "Plasma/SQL/QueryExpressions/Column.html", "link": "Plasma/SQL/QueryExpressions/Column.html#method_getSQL", "name": "Plasma\\SQL\\QueryExpressions\\Column::getSQL", "doc": "&quot;Get the SQL string for this.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\QueryExpressions\\Column", "fromLink": "Plasma/SQL/QueryExpressions/Column.html", "link": "Plasma/SQL/QueryExpressions/Column.html#method_getIdentifier", "name": "Plasma\\SQL\\QueryExpressions\\Column::getIdentifier", "doc": "&quot;Get the conflict identifier.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\QueryExpressions\\Column", "fromLink": "Plasma/SQL/QueryExpressions/Column.html", "link": "Plasma/SQL/QueryExpressions/Column.html#method___toString", "name": "Plasma\\SQL\\QueryExpressions\\Column::__toString", "doc": "&quot;Turns the expression into a SQL string.&quot;"},
            {"type": "Class", "fromName": "Plasma\\SQL\\QueryExpressions", "fromLink": "Plasma/SQL/QueryExpressions.html", "link": "Plasma/SQL/QueryExpressions/Constraint.html", "name": "Plasma\\SQL\\QueryExpressions\\Constraint", "doc": "&quot;Represents a constraint. Currently only used with PostgreSQL.&quot;"},
                                                        {"type": "Method", "fromName": "Plasma\\SQL\\QueryExpressions\\Constraint", "fromLink": "Plasma/SQL/QueryExpressions/Constraint.html", "link": "Plasma/SQL/QueryExpressions/Constraint.html#method___construct", "name": "Plasma\\SQL\\QueryExpressions\\Constraint::__construct", "doc": "&quot;Constructor.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\QueryExpressions\\Constraint", "fromLink": "Plasma/SQL/QueryExpressions/Constraint.html", "link": "Plasma/SQL/QueryExpressions/Constraint.html#method_getName", "name": "Plasma\\SQL\\QueryExpressions\\Constraint::getName", "doc": "&quot;Get the constraint name.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\QueryExpressions\\Constraint", "fromLink": "Plasma/SQL/QueryExpressions/Constraint.html", "link": "Plasma/SQL/QueryExpressions/Constraint.html#method_getIdentifier", "name": "Plasma\\SQL\\QueryExpressions\\Constraint::getIdentifier", "doc": "&quot;Get the conflict identifier.&quot;"},
            {"type": "Class", "fromName": "Plasma\\SQL\\QueryExpressions", "fromLink": "Plasma/SQL/QueryExpressions.html", "link": "Plasma/SQL/QueryExpressions/Fragment.html", "name": "Plasma\\SQL\\QueryExpressions\\Fragment", "doc": "&quot;Represents a raw SQL string.&quot;"},
                                                        {"type": "Method", "fromName": "Plasma\\SQL\\QueryExpressions\\Fragment", "fromLink": "Plasma/SQL/QueryExpressions/Fragment.html", "link": "Plasma/SQL/QueryExpressions/Fragment.html#method___construct", "name": "Plasma\\SQL\\QueryExpressions\\Fragment::__construct", "doc": "&quot;Constructor.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\QueryExpressions\\Fragment", "fromLink": "Plasma/SQL/QueryExpressions/Fragment.html", "link": "Plasma/SQL/QueryExpressions/Fragment.html#method_allowEscape", "name": "Plasma\\SQL\\QueryExpressions\\Fragment::allowEscape", "doc": "&quot;Whether the fragment allows escaping. Always &lt;code&gt;false&lt;\/code&gt;.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\QueryExpressions\\Fragment", "fromLink": "Plasma/SQL/QueryExpressions/Fragment.html", "link": "Plasma/SQL/QueryExpressions/Fragment.html#method_getSQL", "name": "Plasma\\SQL\\QueryExpressions\\Fragment::getSQL", "doc": "&quot;Get the SQL string for this.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\QueryExpressions\\Fragment", "fromLink": "Plasma/SQL/QueryExpressions/Fragment.html", "link": "Plasma/SQL/QueryExpressions/Fragment.html#method___toString", "name": "Plasma\\SQL\\QueryExpressions\\Fragment::__toString", "doc": "&quot;Turns the expression into a SQL string.&quot;"},
            {"type": "Class", "fromName": "Plasma\\SQL\\QueryExpressions", "fromLink": "Plasma/SQL/QueryExpressions.html", "link": "Plasma/SQL/QueryExpressions/FragmentedWhere.html", "name": "Plasma\\SQL\\QueryExpressions\\FragmentedWhere", "doc": "&quot;Represents a fragmented WHERE clausel.&quot;"},
                                                        {"type": "Method", "fromName": "Plasma\\SQL\\QueryExpressions\\FragmentedWhere", "fromLink": "Plasma/SQL/QueryExpressions/FragmentedWhere.html", "link": "Plasma/SQL/QueryExpressions/FragmentedWhere.html#method___construct", "name": "Plasma\\SQL\\QueryExpressions\\FragmentedWhere::__construct", "doc": "&quot;Constructor.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\QueryExpressions\\FragmentedWhere", "fromLink": "Plasma/SQL/QueryExpressions/FragmentedWhere.html", "link": "Plasma/SQL/QueryExpressions/FragmentedWhere.html#method_getSQL", "name": "Plasma\\SQL\\QueryExpressions\\FragmentedWhere::getSQL", "doc": "&quot;Get the SQL string for this.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\QueryExpressions\\FragmentedWhere", "fromLink": "Plasma/SQL/QueryExpressions/FragmentedWhere.html", "link": "Plasma/SQL/QueryExpressions/FragmentedWhere.html#method_getParameters", "name": "Plasma\\SQL\\QueryExpressions\\FragmentedWhere::getParameters", "doc": "&quot;Get the parameters.&quot;"},
            {"type": "Class", "fromName": "Plasma\\SQL\\QueryExpressions", "fromLink": "Plasma/SQL/QueryExpressions.html", "link": "Plasma/SQL/QueryExpressions/GroupBy.html", "name": "Plasma\\SQL\\QueryExpressions\\GroupBy", "doc": "&quot;Represents a GROUP BY clausel.&quot;"},
                                                        {"type": "Method", "fromName": "Plasma\\SQL\\QueryExpressions\\GroupBy", "fromLink": "Plasma/SQL/QueryExpressions/GroupBy.html", "link": "Plasma/SQL/QueryExpressions/GroupBy.html#method___construct", "name": "Plasma\\SQL\\QueryExpressions\\GroupBy::__construct", "doc": "&quot;Constructor.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\QueryExpressions\\GroupBy", "fromLink": "Plasma/SQL/QueryExpressions/GroupBy.html", "link": "Plasma/SQL/QueryExpressions/GroupBy.html#method_getColumn", "name": "Plasma\\SQL\\QueryExpressions\\GroupBy::getColumn", "doc": "&quot;Get the column.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\QueryExpressions\\GroupBy", "fromLink": "Plasma/SQL/QueryExpressions/GroupBy.html", "link": "Plasma/SQL/QueryExpressions/GroupBy.html#method_getSQL", "name": "Plasma\\SQL\\QueryExpressions\\GroupBy::getSQL", "doc": "&quot;Get the SQL string for this.&quot;"},
            {"type": "Class", "fromName": "Plasma\\SQL\\QueryExpressions", "fromLink": "Plasma/SQL/QueryExpressions.html", "link": "Plasma/SQL/QueryExpressions/Join.html", "name": "Plasma\\SQL\\QueryExpressions\\Join", "doc": "&quot;Represents a JOIN clausel.&quot;"},
                                                        {"type": "Method", "fromName": "Plasma\\SQL\\QueryExpressions\\Join", "fromLink": "Plasma/SQL/QueryExpressions/Join.html", "link": "Plasma/SQL/QueryExpressions/Join.html#method___construct", "name": "Plasma\\SQL\\QueryExpressions\\Join::__construct", "doc": "&quot;Constructor.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\QueryExpressions\\Join", "fromLink": "Plasma/SQL/QueryExpressions/Join.html", "link": "Plasma/SQL/QueryExpressions/Join.html#method_getType", "name": "Plasma\\SQL\\QueryExpressions\\Join::getType", "doc": "&quot;Get the type.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\QueryExpressions\\Join", "fromLink": "Plasma/SQL/QueryExpressions/Join.html", "link": "Plasma/SQL/QueryExpressions/Join.html#method_getTable", "name": "Plasma\\SQL\\QueryExpressions\\Join::getTable", "doc": "&quot;Get the table.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\QueryExpressions\\Join", "fromLink": "Plasma/SQL/QueryExpressions/Join.html", "link": "Plasma/SQL/QueryExpressions/Join.html#method_getOns", "name": "Plasma\\SQL\\QueryExpressions\\Join::getOns", "doc": "&quot;Get the added ON clausels.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\QueryExpressions\\Join", "fromLink": "Plasma/SQL/QueryExpressions/Join.html", "link": "Plasma/SQL/QueryExpressions/Join.html#method_addOn", "name": "Plasma\\SQL\\QueryExpressions\\Join::addOn", "doc": "&quot;Adds an ON clausel.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\QueryExpressions\\Join", "fromLink": "Plasma/SQL/QueryExpressions/Join.html", "link": "Plasma/SQL/QueryExpressions/Join.html#method_getSQL", "name": "Plasma\\SQL\\QueryExpressions\\Join::getSQL", "doc": "&quot;Get the SQL string for this.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\QueryExpressions\\Join", "fromLink": "Plasma/SQL/QueryExpressions/Join.html", "link": "Plasma/SQL/QueryExpressions/Join.html#method___toString", "name": "Plasma\\SQL\\QueryExpressions\\Join::__toString", "doc": "&quot;Turns the expression into a SQL string.&quot;"},
            {"type": "Class", "fromName": "Plasma\\SQL\\QueryExpressions", "fromLink": "Plasma/SQL/QueryExpressions.html", "link": "Plasma/SQL/QueryExpressions/On.html", "name": "Plasma\\SQL\\QueryExpressions\\On", "doc": "&quot;Represents an ON clausel.&quot;"},
                                                        {"type": "Method", "fromName": "Plasma\\SQL\\QueryExpressions\\On", "fromLink": "Plasma/SQL/QueryExpressions/On.html", "link": "Plasma/SQL/QueryExpressions/On.html#method___construct", "name": "Plasma\\SQL\\QueryExpressions\\On::__construct", "doc": "&quot;Constructor.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\QueryExpressions\\On", "fromLink": "Plasma/SQL/QueryExpressions/On.html", "link": "Plasma/SQL/QueryExpressions/On.html#method_getSQL", "name": "Plasma\\SQL\\QueryExpressions\\On::getSQL", "doc": "&quot;Get the SQL string for this.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\QueryExpressions\\On", "fromLink": "Plasma/SQL/QueryExpressions/On.html", "link": "Plasma/SQL/QueryExpressions/On.html#method___toString", "name": "Plasma\\SQL\\QueryExpressions\\On::__toString", "doc": "&quot;Turns the expression into a SQL string.&quot;"},
            {"type": "Class", "fromName": "Plasma\\SQL\\QueryExpressions", "fromLink": "Plasma/SQL/QueryExpressions.html", "link": "Plasma/SQL/QueryExpressions/OrderBy.html", "name": "Plasma\\SQL\\QueryExpressions\\OrderBy", "doc": "&quot;Represents an ORDER BY clausel.&quot;"},
                                                        {"type": "Method", "fromName": "Plasma\\SQL\\QueryExpressions\\OrderBy", "fromLink": "Plasma/SQL/QueryExpressions/OrderBy.html", "link": "Plasma/SQL/QueryExpressions/OrderBy.html#method___construct", "name": "Plasma\\SQL\\QueryExpressions\\OrderBy::__construct", "doc": "&quot;Constructor.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\QueryExpressions\\OrderBy", "fromLink": "Plasma/SQL/QueryExpressions/OrderBy.html", "link": "Plasma/SQL/QueryExpressions/OrderBy.html#method_getColumn", "name": "Plasma\\SQL\\QueryExpressions\\OrderBy::getColumn", "doc": "&quot;Get the column.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\QueryExpressions\\OrderBy", "fromLink": "Plasma/SQL/QueryExpressions/OrderBy.html", "link": "Plasma/SQL/QueryExpressions/OrderBy.html#method_isDescending", "name": "Plasma\\SQL\\QueryExpressions\\OrderBy::isDescending", "doc": "&quot;Whether the sorting is descending.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\QueryExpressions\\OrderBy", "fromLink": "Plasma/SQL/QueryExpressions/OrderBy.html", "link": "Plasma/SQL/QueryExpressions/OrderBy.html#method_getSQL", "name": "Plasma\\SQL\\QueryExpressions\\OrderBy::getSQL", "doc": "&quot;Get the SQL string for this.&quot;"},
            {"type": "Class", "fromName": "Plasma\\SQL\\QueryExpressions", "fromLink": "Plasma/SQL/QueryExpressions.html", "link": "Plasma/SQL/QueryExpressions/Parameter.html", "name": "Plasma\\SQL\\QueryExpressions\\Parameter", "doc": "&quot;Represents a parameter.&quot;"},
                                                        {"type": "Method", "fromName": "Plasma\\SQL\\QueryExpressions\\Parameter", "fromLink": "Plasma/SQL/QueryExpressions/Parameter.html", "link": "Plasma/SQL/QueryExpressions/Parameter.html#method___construct", "name": "Plasma\\SQL\\QueryExpressions\\Parameter::__construct", "doc": "&quot;Constructor.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\QueryExpressions\\Parameter", "fromLink": "Plasma/SQL/QueryExpressions/Parameter.html", "link": "Plasma/SQL/QueryExpressions/Parameter.html#method_hasValue", "name": "Plasma\\SQL\\QueryExpressions\\Parameter::hasValue", "doc": "&quot;Whether this parameter has a value. If not, the QueryBuilder is expected to throw an Exception.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\QueryExpressions\\Parameter", "fromLink": "Plasma/SQL/QueryExpressions/Parameter.html", "link": "Plasma/SQL/QueryExpressions/Parameter.html#method_getValue", "name": "Plasma\\SQL\\QueryExpressions\\Parameter::getValue", "doc": "&quot;Get the value.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\QueryExpressions\\Parameter", "fromLink": "Plasma/SQL/QueryExpressions/Parameter.html", "link": "Plasma/SQL/QueryExpressions/Parameter.html#method_setValue", "name": "Plasma\\SQL\\QueryExpressions\\Parameter::setValue", "doc": "&quot;Set the value.&quot;"},
            {"type": "Class", "fromName": "Plasma\\SQL\\QueryExpressions", "fromLink": "Plasma/SQL/QueryExpressions.html", "link": "Plasma/SQL/QueryExpressions/Subquery.html", "name": "Plasma\\SQL\\QueryExpressions\\Subquery", "doc": "&quot;Represents a subquery. Interoperable with all Plasma SQL query builder.&quot;"},
                                                        {"type": "Method", "fromName": "Plasma\\SQL\\QueryExpressions\\Subquery", "fromLink": "Plasma/SQL/QueryExpressions/Subquery.html", "link": "Plasma/SQL/QueryExpressions/Subquery.html#method___construct", "name": "Plasma\\SQL\\QueryExpressions\\Subquery::__construct", "doc": "&quot;Constructor.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\QueryExpressions\\Subquery", "fromLink": "Plasma/SQL/QueryExpressions/Subquery.html", "link": "Plasma/SQL/QueryExpressions/Subquery.html#method_getSQL", "name": "Plasma\\SQL\\QueryExpressions\\Subquery::getSQL", "doc": "&quot;Get the SQL string for this.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\QueryExpressions\\Subquery", "fromLink": "Plasma/SQL/QueryExpressions/Subquery.html", "link": "Plasma/SQL/QueryExpressions/Subquery.html#method_getParameters", "name": "Plasma\\SQL\\QueryExpressions\\Subquery::getParameters", "doc": "&quot;Get the parameters.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\QueryExpressions\\Subquery", "fromLink": "Plasma/SQL/QueryExpressions/Subquery.html", "link": "Plasma/SQL/QueryExpressions/Subquery.html#method___toString", "name": "Plasma\\SQL\\QueryExpressions\\Subquery::__toString", "doc": "&quot;Turns the expression into a SQL string.&quot;"},
            {"type": "Class", "fromName": "Plasma\\SQL\\QueryExpressions", "fromLink": "Plasma/SQL/QueryExpressions.html", "link": "Plasma/SQL/QueryExpressions/Table.html", "name": "Plasma\\SQL\\QueryExpressions\\Table", "doc": "&quot;Represents a table with optional alias and escaping.&quot;"},
                                                        {"type": "Method", "fromName": "Plasma\\SQL\\QueryExpressions\\Table", "fromLink": "Plasma/SQL/QueryExpressions/Table.html", "link": "Plasma/SQL/QueryExpressions/Table.html#method___construct", "name": "Plasma\\SQL\\QueryExpressions\\Table::__construct", "doc": "&quot;Constructor.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\QueryExpressions\\Table", "fromLink": "Plasma/SQL/QueryExpressions/Table.html", "link": "Plasma/SQL/QueryExpressions/Table.html#method_getTable", "name": "Plasma\\SQL\\QueryExpressions\\Table::getTable", "doc": "&quot;Get the table.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\QueryExpressions\\Table", "fromLink": "Plasma/SQL/QueryExpressions/Table.html", "link": "Plasma/SQL/QueryExpressions/Table.html#method_getAlias", "name": "Plasma\\SQL\\QueryExpressions\\Table::getAlias", "doc": "&quot;Get the alias.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\QueryExpressions\\Table", "fromLink": "Plasma/SQL/QueryExpressions/Table.html", "link": "Plasma/SQL/QueryExpressions/Table.html#method_allowEscape", "name": "Plasma\\SQL\\QueryExpressions\\Table::allowEscape", "doc": "&quot;Whether the table allows escaping.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\QueryExpressions\\Table", "fromLink": "Plasma/SQL/QueryExpressions/Table.html", "link": "Plasma/SQL/QueryExpressions/Table.html#method_getSQL", "name": "Plasma\\SQL\\QueryExpressions\\Table::getSQL", "doc": "&quot;Get the SQL string for this.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\QueryExpressions\\Table", "fromLink": "Plasma/SQL/QueryExpressions/Table.html", "link": "Plasma/SQL/QueryExpressions/Table.html#method___toString", "name": "Plasma\\SQL\\QueryExpressions\\Table::__toString", "doc": "&quot;Turns the expression into a SQL string.&quot;"},
            {"type": "Class", "fromName": "Plasma\\SQL\\QueryExpressions", "fromLink": "Plasma/SQL/QueryExpressions.html", "link": "Plasma/SQL/QueryExpressions/Union.html", "name": "Plasma\\SQL\\QueryExpressions\\Union", "doc": "&quot;Represents an UNION clausel.&quot;"},
                                                        {"type": "Method", "fromName": "Plasma\\SQL\\QueryExpressions\\Union", "fromLink": "Plasma/SQL/QueryExpressions/Union.html", "link": "Plasma/SQL/QueryExpressions/Union.html#method___construct", "name": "Plasma\\SQL\\QueryExpressions\\Union::__construct", "doc": "&quot;Constructor.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\QueryExpressions\\Union", "fromLink": "Plasma/SQL/QueryExpressions/Union.html", "link": "Plasma/SQL/QueryExpressions/Union.html#method_getSQL", "name": "Plasma\\SQL\\QueryExpressions\\Union::getSQL", "doc": "&quot;Get the SQL string for this.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\QueryExpressions\\Union", "fromLink": "Plasma/SQL/QueryExpressions/Union.html", "link": "Plasma/SQL/QueryExpressions/Union.html#method_getParameters", "name": "Plasma\\SQL\\QueryExpressions\\Union::getParameters", "doc": "&quot;Get the parameters.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\QueryExpressions\\Union", "fromLink": "Plasma/SQL/QueryExpressions/Union.html", "link": "Plasma/SQL/QueryExpressions/Union.html#method___toString", "name": "Plasma\\SQL\\QueryExpressions\\Union::__toString", "doc": "&quot;Turns the expression into a SQL string.&quot;"},
            {"type": "Class", "fromName": "Plasma\\SQL\\QueryExpressions", "fromLink": "Plasma/SQL/QueryExpressions.html", "link": "Plasma/SQL/QueryExpressions/UnionAll.html", "name": "Plasma\\SQL\\QueryExpressions\\UnionAll", "doc": "&quot;Represents an UNION ALL clausel.&quot;"},
                                                        {"type": "Method", "fromName": "Plasma\\SQL\\QueryExpressions\\UnionAll", "fromLink": "Plasma/SQL/QueryExpressions/UnionAll.html", "link": "Plasma/SQL/QueryExpressions/UnionAll.html#method___construct", "name": "Plasma\\SQL\\QueryExpressions\\UnionAll::__construct", "doc": "&quot;Constructor.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\QueryExpressions\\UnionAll", "fromLink": "Plasma/SQL/QueryExpressions/UnionAll.html", "link": "Plasma/SQL/QueryExpressions/UnionAll.html#method_getSQL", "name": "Plasma\\SQL\\QueryExpressions\\UnionAll::getSQL", "doc": "&quot;Get the SQL string for this.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\QueryExpressions\\UnionAll", "fromLink": "Plasma/SQL/QueryExpressions/UnionAll.html", "link": "Plasma/SQL/QueryExpressions/UnionAll.html#method_getParameters", "name": "Plasma\\SQL\\QueryExpressions\\UnionAll::getParameters", "doc": "&quot;Get the parameters.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\QueryExpressions\\UnionAll", "fromLink": "Plasma/SQL/QueryExpressions/UnionAll.html", "link": "Plasma/SQL/QueryExpressions/UnionAll.html#method___toString", "name": "Plasma\\SQL\\QueryExpressions\\UnionAll::__toString", "doc": "&quot;Turns the expression into a SQL string.&quot;"},
            {"type": "Class", "fromName": "Plasma\\SQL\\QueryExpressions", "fromLink": "Plasma/SQL/QueryExpressions.html", "link": "Plasma/SQL/QueryExpressions/Where.html", "name": "Plasma\\SQL\\QueryExpressions\\Where", "doc": "&quot;Represents a WHERE clausel.&quot;"},
                                                        {"type": "Method", "fromName": "Plasma\\SQL\\QueryExpressions\\Where", "fromLink": "Plasma/SQL/QueryExpressions/Where.html", "link": "Plasma/SQL/QueryExpressions/Where.html#method___construct", "name": "Plasma\\SQL\\QueryExpressions\\Where::__construct", "doc": "&quot;Constructor.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\QueryExpressions\\Where", "fromLink": "Plasma/SQL/QueryExpressions/Where.html", "link": "Plasma/SQL/QueryExpressions/Where.html#method_getSQL", "name": "Plasma\\SQL\\QueryExpressions\\Where::getSQL", "doc": "&quot;Get the SQL string for this.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\QueryExpressions\\Where", "fromLink": "Plasma/SQL/QueryExpressions/Where.html", "link": "Plasma/SQL/QueryExpressions/Where.html#method_getParameter", "name": "Plasma\\SQL\\QueryExpressions\\Where::getParameter", "doc": "&quot;Get the raw parameter.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\QueryExpressions\\Where", "fromLink": "Plasma/SQL/QueryExpressions/Where.html", "link": "Plasma/SQL/QueryExpressions/Where.html#method_getParameters", "name": "Plasma\\SQL\\QueryExpressions\\Where::getParameters", "doc": "&quot;Get the parameter wrapped in an array.&quot;"},
            {"type": "Class", "fromName": "Plasma\\SQL\\QueryExpressions", "fromLink": "Plasma/SQL/QueryExpressions.html", "link": "Plasma/SQL/QueryExpressions/WhereBuilder.html", "name": "Plasma\\SQL\\QueryExpressions\\WhereBuilder", "doc": "&quot;Represents a WhereBuilder inside a WHERE clausel.&quot;"},
                                                        {"type": "Method", "fromName": "Plasma\\SQL\\QueryExpressions\\WhereBuilder", "fromLink": "Plasma/SQL/QueryExpressions/WhereBuilder.html", "link": "Plasma/SQL/QueryExpressions/WhereBuilder.html#method___construct", "name": "Plasma\\SQL\\QueryExpressions\\WhereBuilder::__construct", "doc": "&quot;Constructor.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\QueryExpressions\\WhereBuilder", "fromLink": "Plasma/SQL/QueryExpressions/WhereBuilder.html", "link": "Plasma/SQL/QueryExpressions/WhereBuilder.html#method_getSQL", "name": "Plasma\\SQL\\QueryExpressions\\WhereBuilder::getSQL", "doc": "&quot;Get the SQL string for this.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\QueryExpressions\\WhereBuilder", "fromLink": "Plasma/SQL/QueryExpressions/WhereBuilder.html", "link": "Plasma/SQL/QueryExpressions/WhereBuilder.html#method_getParameters", "name": "Plasma\\SQL\\QueryExpressions\\WhereBuilder::getParameters", "doc": "&quot;Get the parameters.&quot;"},
            {"type": "Class", "fromName": "Plasma\\SQL", "fromLink": "Plasma/SQL.html", "link": "Plasma/SQL/WhereBuilder.html", "name": "Plasma\\SQL\\WhereBuilder", "doc": "&quot;Used to build more complex WHERE and HAVING clausels.&quot;"},
                                                        {"type": "Method", "fromName": "Plasma\\SQL\\WhereBuilder", "fromLink": "Plasma/SQL/WhereBuilder.html", "link": "Plasma/SQL/WhereBuilder.html#method_createWhere", "name": "Plasma\\SQL\\WhereBuilder::createWhere", "doc": "&quot;Creates a WHERE expression.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\WhereBuilder", "fromLink": "Plasma/SQL/WhereBuilder.html", "link": "Plasma/SQL/WhereBuilder.html#method_and", "name": "Plasma\\SQL\\WhereBuilder::and", "doc": "&quot;Put the previous WHERE clausel with a logical AND constraint to this WHERE clausel.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\WhereBuilder", "fromLink": "Plasma/SQL/WhereBuilder.html", "link": "Plasma/SQL/WhereBuilder.html#method_or", "name": "Plasma\\SQL\\WhereBuilder::or", "doc": "&quot;Put the previous WHERE clausel with a logical OR constraint to this WHERE clausel.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\WhereBuilder", "fromLink": "Plasma/SQL/WhereBuilder.html", "link": "Plasma/SQL/WhereBuilder.html#method_andBuilder", "name": "Plasma\\SQL\\WhereBuilder::andBuilder", "doc": "&quot;Put the WHERE builder with a logical AND constraint to this builder. The WHERE clausel of the builder gets wrapped into parenthesis.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\WhereBuilder", "fromLink": "Plasma/SQL/WhereBuilder.html", "link": "Plasma/SQL/WhereBuilder.html#method_orBuilder", "name": "Plasma\\SQL\\WhereBuilder::orBuilder", "doc": "&quot;Put the WHERE builder with a logical OR constraint to this builder. The WHERE clausel of the builder gets wrapped into parenthesis.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\WhereBuilder", "fromLink": "Plasma/SQL/WhereBuilder.html", "link": "Plasma/SQL/WhereBuilder.html#method_isEmpty", "name": "Plasma\\SQL\\WhereBuilder::isEmpty", "doc": "&quot;Whether the where builder is empty (no clausels).&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\WhereBuilder", "fromLink": "Plasma/SQL/WhereBuilder.html", "link": "Plasma/SQL/WhereBuilder.html#method_getSQL", "name": "Plasma\\SQL\\WhereBuilder::getSQL", "doc": "&quot;Get the SQL string for the where clausel.&quot;"},
                    {"type": "Method", "fromName": "Plasma\\SQL\\WhereBuilder", "fromLink": "Plasma/SQL/WhereBuilder.html", "link": "Plasma/SQL/WhereBuilder.html#method_getParameters", "name": "Plasma\\SQL\\WhereBuilder::getParameters", "doc": "&quot;Get the parameters.&quot;"},
            
                                        // Fix trailing commas in the index
        {}
    ];

    /** Tokenizes strings by namespaces and functions */
    function tokenizer(term) {
        if (!term) {
            return [];
        }

        var tokens = [term];
        var meth = term.indexOf('::');

        // Split tokens into methods if "::" is found.
        if (meth > -1) {
            tokens.push(term.substr(meth + 2));
            term = term.substr(0, meth - 2);
        }

        // Split by namespace or fake namespace.
        if (term.indexOf('\\') > -1) {
            tokens = tokens.concat(term.split('\\'));
        } else if (term.indexOf('_') > 0) {
            tokens = tokens.concat(term.split('_'));
        }

        // Merge in splitting the string by case and return
        tokens = tokens.concat(term.match(/(([A-Z]?[^A-Z]*)|([a-z]?[^a-z]*))/g).slice(0,-1));

        return tokens;
    };

    root.Sami = {
        /**
         * Cleans the provided term. If no term is provided, then one is
         * grabbed from the query string "search" parameter.
         */
        cleanSearchTerm: function(term) {
            // Grab from the query string
            if (typeof term === 'undefined') {
                var name = 'search';
                var regex = new RegExp("[\\?&]" + name + "=([^&#]*)");
                var results = regex.exec(location.search);
                if (results === null) {
                    return null;
                }
                term = decodeURIComponent(results[1].replace(/\+/g, " "));
            }

            return term.replace(/<(?:.|\n)*?>/gm, '');
        },

        /** Searches through the index for a given term */
        search: function(term) {
            // Create a new search index if needed
            if (!bhIndex) {
                bhIndex = new Bloodhound({
                    limit: 500,
                    local: searchIndex,
                    datumTokenizer: function (d) {
                        return tokenizer(d.name);
                    },
                    queryTokenizer: Bloodhound.tokenizers.whitespace
                });
                bhIndex.initialize();
            }

            results = [];
            bhIndex.get(term, function(matches) {
                results = matches;
            });

            if (!rootPath) {
                return results;
            }

            // Fix the element links based on the current page depth.
            return $.map(results, function(ele) {
                if (ele.link.indexOf('..') > -1) {
                    return ele;
                }
                ele.link = rootPath + ele.link;
                if (ele.fromLink) {
                    ele.fromLink = rootPath + ele.fromLink;
                }
                return ele;
            });
        },

        /** Get a search class for a specific type */
        getSearchClass: function(type) {
            return searchTypeClasses[type] || searchTypeClasses['_'];
        },

        /** Add the left-nav tree to the site */
        injectApiTree: function(ele) {
            ele.html(treeHtml);
        }
    };

    $(function() {
        // Modify the HTML to work correctly based on the current depth
        rootPath = $('body').attr('data-root-path');
        treeHtml = treeHtml.replace(/href="/g, 'href="' + rootPath);
        Sami.injectApiTree($('#api-tree'));
    });

    return root.Sami;
})(window);

$(function() {

    // Enable the version switcher
    $('#version-switcher').change(function() {
        window.location = $(this).val()
    });

    
        // Toggle left-nav divs on click
        $('#api-tree .hd span').click(function() {
            $(this).parent().parent().toggleClass('opened');
        });

        // Expand the parent namespaces of the current page.
        var expected = $('body').attr('data-name');

        if (expected) {
            // Open the currently selected node and its parents.
            var container = $('#api-tree');
            var node = $('#api-tree li[data-name="' + expected + '"]');
            // Node might not be found when simulating namespaces
            if (node.length > 0) {
                node.addClass('active').addClass('opened');
                node.parents('li').addClass('opened');
                var scrollPos = node.offset().top - container.offset().top + container.scrollTop();
                // Position the item nearer to the top of the screen.
                scrollPos -= 200;
                container.scrollTop(scrollPos);
            }
        }

    
    
        var form = $('#search-form .typeahead');
        form.typeahead({
            hint: true,
            highlight: true,
            minLength: 1
        }, {
            name: 'search',
            displayKey: 'name',
            source: function (q, cb) {
                cb(Sami.search(q));
            }
        });

        // The selection is direct-linked when the user selects a suggestion.
        form.on('typeahead:selected', function(e, suggestion) {
            window.location = suggestion.link;
        });

        // The form is submitted when the user hits enter.
        form.keypress(function (e) {
            if (e.which == 13) {
                $('#search-form').submit();
                return true;
            }
        });

    
});


