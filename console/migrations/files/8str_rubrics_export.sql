select c.tid, c.name, p.tid as ptid, p.name as pname
from pdxtaxonomy_term_data as c
	left join pdxtaxonomy_term_hierarchy as h
		on c.tid = h.tid
	left join pdxtaxonomy_term_data as p
		on p.tid = h.parent
where c.vid=2
order by p.tid asc