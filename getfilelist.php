<?php
$DEBUG=false;

//
// Script to generate files for sitemap.org format
//
function GetFilesInDirectory($dir)
{
	if ($DEBUG===true)
	{
	 	Print ("Directory: ");
	 	Print ($dir);
	 	Print ("<br>\n");	 
	}
	
	 if (is_dir($dir))
	 {
		 if ($dh=opendir($dir))
		 {
			  while (($file=readdir($dh)))
			  {
			  	
				  	if ($DEBUG===true)
					{			  	
					    Print ("File:".$file."<br />");
				  		Print (strpos($dir,"blog"));
					}

					// Deal with the files					
				  	if (strpos($file,".") > 0)
				    {
				    	if (((strpos($file,".html") > 0) || 
				    		(strpos($file,".php") > 0)))  
				    	{
				  			print ( "<url><loc>http://www.brightonbloggers.com/");
				  			if ($dir <> ".")
				  			{
				  				print ($dir);
				  				print ("/");
				  			}
				  			print (str_replace(" ","%20",$file));				  			
							print ("</loc><changefreq>");
							if (!(strpos($dir,"blog") === false))
							{
								print ("daily");
							}
							else
							{
								print ("monthly");
							}
							print ( "</changefreq><priority>");
							if (!(strpos($dir,"blog") === false) && (strpos($dir,"archive") === false))
							{
								print ("0.8"); // rate active blogs as 0.8 
							}
							elseif ((!(strpos($dir,"blog") === false) && (!(strpos($dir,"archive") === false))) ||
									(!(strpos($dir,"blog") === false) && (!(strpos($dir,"labels") === false))))
							{
								print ("0.7"); // rate archive blogs and labels pages as 0.7
							}
							else
							{
								print ("0.5"); // rate everything else as 0.5
							}					
							print ("</priority><lastmod>");
							print (date("c"));
							print("</lastmod></url>\n");
				    	}
				    }
				    else
				    {
				    	
				    	// Deal with folders
				    	if (($file == ".") || 
				    		($file == "..") || 
				    		($file == "comment") || 
				    		($file == "css") || 
				    		($file == "includes") || 
				    		($file == "styles") ||
				    		($file == "reports"))	
				    	{
				    		// Skip these dirs	
				    	}
				    	else
				    	{
					    	if ($dir <> ".")
					    	{
					  			GetFilesInDirectory($dir."/".$file);				  		
							}
							else
							{
					    		GetFilesInDirectory($file);
							}
				    	}
				    }
		  		}
		  		closedir($dh);
		 } 		
	 } 
}

print ("<?xml version=\"1.0\" encoding=\"utf-8\" ?>\n");
print ("<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n");
GetFilesInDirectory(".");
print ("</urlset>\n");

?>