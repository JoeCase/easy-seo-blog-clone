CKEDITOR.plugins.add( 'wixgallery', {
	requires: 'jquery',
	lang: 'en',
	icons: 'wixgallery',
	init: function( editor ) {
		
		// Add the WIX gallery button.
		editor.addCommand( 'wixgallery', new CKEDITOR.wixgalleryCommand() );
		
		if ( editor.ui.addButton ) {
			editor.ui.addButton( 'wixgallery', {
				label: editor.lang.wixgallery.title,
				command: 'wixgallery',
				toolbar: 'insert,5'
			});
		}
		
		/* Wait for DOM ready event */
		editor.on('contentDom', function(ev) {
			var domHandlers = [],
				editor = ev.editor,
				window = editor.document.getWindow().$;
			
			// ----------------- //
			// Initialize jQuery //
			// ----------------- //
			
			editor.jQueryDocument = editor.document.$;
			if (!editor.jQuery) {
				editor.jQuery = editor.$ = function( selector, context ) {
					// The jQuery object is actually just the init constructor 'enhanced'
					return new jQuery.fn.init( selector, context, jQuery( editor.jQueryDocument ) );
				};
				
				for (var m in jQuery) {
					editor.jQuery[m] = jQuery[m];
				}
			}
			
			// ------------------------ //
			// Define wixgallery styles //
			// ------------------------ //

			// .img-wixgallery: ( .nowrap | .wrap ) | ( .left | .center | .right  )
			editor.document.appendStyleText(
				"ul,ol {overflow: hidden; list-style-position: inside;}" + // Lists CSS reset (required for floated images next to list)
				".img-wixgallery {position: relative; display: block;}" +
				".img-wixgallery-axis { position: absolute; top: -20px; bottom: -20px; left: 50%; margin-left: -1px; width: 0px; border: 1px dashed #00f; z-index: -1; display: none; }" +
				".img-wixgallery-cover {cursor: move; position: absolute; top: 0; right: 0; bottom: 0; left: 0;}" +
				".img-wixgallery-handle {position: absolute; font-size: 0.1px; display: none; width: 23px; height: 21px; background: transparent url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABcAAAGXCAYAAABY7cvMAAAACXBIWXMAAA7DAAAOwwHHb6hkAAAAIGNIUk0AAHolAACAgwAA+f8AAIDpAAB1MAAA6mAAADqYAAAXb5JfxUYAABe8SURBVHja7J17VBN3osfn4J57zj3+4/YPz90/dretFqwuCgRigKyKCGIQTALhIXWxvhVLVURtfMBxWbXHtbbedldd27JetBUVdOuqoCIWkAhaBRWVlzwV5JmQB3l+7x9kxkkykyfe092bnPM9CTM/PxNmkvn+5nu+DgQA4k2J8MK9cC/cC/fCvXAv3D04+QAg1gMqIwB3pQcUACIJq8cvdHq9zgDAU2m02iGCIHwocl5eHkdnArQORKQddDhGrdWZCIKYTLJ9JBKJWGsCNA4EOB4jV2tAEASH2iVz585dpTEBSgci0g46HDOoUoMgiGgS/h98Pn+d0gTIHQhwPGZgDC6wgCuMwKADEWkHHY7pY4IPG4E+ByL233c4ppcJPmAEelhEpB0c0/771Gu2sS+Y4K+MQJeBWcDYuyYFsI/tVjLAe4xAu4Fd5AYA++M6mOAvjECrwb4A++ufG4A2aziPz1/XaQCa9J6pWQ+0jDDA2w3AU72lqANpR/Txz/RAExO8zQA81luKfhDZRB/foAcaRxj2ebcBeKIDHumAh2Y58yDHPtKNvftWazhBEJLGFz2mTgPQYt53rqpFP/ZRvN/UpKfDf0EQhDA2Nra+oanZpDQBI25IaQIePG4wRkREVNNPXD4EQfAJgjhJEMRjgiDaCIJod0PPCYKoJwjiGP2USxAE8SuCINIJgsgnCKKUIIgyN1RCEMQJgiBS6WZB7ppfEQTBM++vODckIAgixAz2sTDRA+Ut4gOVLapPq1rhrvZXNCtybj6yNeh9Pzbq7nTLoTaY3FZV5zD2lT+zNOj5azdzDsvaINeZ7GqiIMPu+j6NAft+bLQ0aL8FC8SHZW14NWr0SF0qPfLKnlga9G+DuKsOy9rQqTbY1URBht31rSM65JU1WBr0b2Zx1n0ma0OTUu+Rnsi1JFxgA38o19vVREGG3fUPBu3Aawd1djUpM9/ueln/KDP8kKwNFf1aRk0UZGCiIAOTMvOp10zjbr3SsMCr21DaO8qqSZn5lNjGXH2pZob/uboN/3ihsatJmfl211/ssgMv7FR7pDPtKnb4yXa1R/r7cxb4wernON6qshB58OyJPv5vLUp2+JfNSvw3TfSDyCb6+K+aR9h3y9EWJT5rHMEhN3S4aQTHWhjgBEFIdv1QYzrVOIDvO9U41eG6vu9Uo+BZP7YVltkatF9YRH322ZsmT8xiy6lrxilBPK9BO2nQiwrvioWFP6mEhffhruILf1JEF962Nej472p1m6u6sP/piNvaVNmJuNO1lgb9nmQtR3K+zuFn2Ycrtrv+4FM54k7XWhr0ZM4CseR8ncW3zR190ajA4oI7lgb91rSgVUlFdTbnFmv5cMV21x9tHkFcgczSoH/pO2tdUlGdx2fFb1qVJFxgA3d0vvbhiu2uP93GAk8uqnPoRBNEUrvrz3eo2OD1rN7owxXDhyvGBJGUes007p/damZ4SlE9q/tX9GsxQSSlxDbmRo+GHe5o3jJBJLW7vuqVHbijGZcj3R0YZYanFtd7PFd8NGwHbj1rJQ+ePdHHNyq07HDr+Tb9ILKJPr5jRMcMX1pcD4WDKwtH6lXrbeEEQUhijl03XWrqw6jR5Ja0RhOKnvYi8ot/2Br05MA59QuOXDJ5YhbzPysyvuUf7DVoJw363zvidkZuR9zOyO2I2xm5HXEzicx4PY642WJvYv/9cYi40w4yLqNH3y5H3BZR9/77dpc5jLiJtIM2z9ZJKH0s/WeHETcZY9OfgdcgesxNLvM44ibSDtrE3CTc44gbsF1Gwl2OuJ0RCXc54nZG5MPpiJseczsrb8Ttjbj/XSJuR/r5RtyONO4RN5nxehxxs8XekzLzPY+4JwoyGJeR0bdbETc96p6Umc+6zKmIe6Igw+bZOgmljyV/9ijiJkFsyzyKuCcKMhiXTcrMfzMRNwl3OeJ2RiTc5YjbVXkjbm/E7Y243fyM//wjbkca94ibzHg9jrjZYu8JIqnnEbcPV8y4jIy+3Yq46VH3BJGUdZlTEbcPV2zzbJ2E0seSP3sUcZMgtmUeRdw+XDHjsgki6ZuJuEm4yxG3MyLhLkfcrsobcXsj7vGJuCP1gMLDiFsFQGxt0D4arXZoPCJunV6vM+8J6jFZrdWZxiPi1pmAvLw8iwPKkas14xJxa02ARCIR0/d79KBKPS4Rt8YEzJ07dxV91wgGVOrXsbW5luxO5K00AXw+f535mz8G71OpX8fWZrg7kbfCyADvVakt2tpscbajGHyYCf5CpX4dW5vhFlE2wzImDTDBu5Xq17G1GWTxM10METg59hUTvEOpfh1bmwHWDW4yhWaKwMmxPUzwNqUaz8nY2gyybnBbL2PSCyZ4y4gazWRsbYY7E3Fbq9MA8KzhTSNqPCNjazPcnci7nQneOKJGA0Ns7aramOCtI2o81bsfbz/SjUXk3QaGfX6/qUnf5WHE3WkAGl/0mAiCkNDh0REREdUPHjcYPYm4G5qaTbGxsfUEQQjpJy6O2VjrzUbrjkG3mQ3+pNnwqVPuZLOxnjAbrTsGXWo2+HSzHxP06cVks8F6YtA8M9jC5oicm48i91c0KzxJLQ5UtqgOlLfYGvS+8mdDVZ3DHkXcd7rl2Pdjo61B7/ux0dSnMXgccx+WtWH+2s2WBp1X9gRdKr3H0+jDsjb4LVhgadB5ZQ1oHdF5HHMflrXht0FcS4POK2vAE7kWTUo9VUt25zLmM1kbfjOLY/n1zytrwINBLR7KX8PdibxZ4bL+UaqtzRZnO7qcZIXfeqVBRb+WgtOjbKZlTDrEBr/6Uk01tsl427rBTV9n/VzaO4pD1Szwi11qqq09UZBh0+C2XsakP7PBz7SrUNippkDORNzWYoX//bkKJ9tfw90Jd1jhf2tR4nirioK7E0UdrH7ODP+qecSjAO3LZiU7/FjLWFTtTuz3WeMIjrYomXfLtsIyfcGzfo8i7lONA9j1Q42tQU8J4lVvOXXN6IlZZJ+9afILi/AatJMGHV14OzK+8CeFJ6mFsPAn1aLCu7YGHXe6dmhTZadHEffmqi7Ef1dra9Bxp2tNB5/KPY65Jefr8J5kraVBLy64gy8aFR7H3JLzdZjMsTLouAIZjjaPeBxzJxXV4a1pQZYGHVcgwzetSpxsV1O1ZHfOiklFdfil7yzLr39cgQyn25Qo7HwNdyfyZoWf71BRbW22ONuREyWzwf/ZPeahJJweZTMtY1JyUT0z/EaPhmpsk/G2dYObvs76uaJfixQ2eNUrDdXW9uGKbRrc1suYxAq/OzCKh3I9BXIm4rYWK/zR8CialK/h7swVU4tZ4I0KLTrVBgruziyXFd4xovN4fs4K71XrPbqqUOhMWMoEj/ziH/qip73QuhlxjxpNuNTUh5hj120N+i3/4Or5nxUZPTGLBUcumSYHzvEatJMG7W1xO2xxT8rMf3Mt7mGDEYMGE4aM49ziHjYB/QYTXhnHfgMytrbX4KbfwMRui7vfCPQYgC69CZ0G4IUB6DfYb3ADLt6ohAx+Bw0mTMrMR7/BhJeGsd9myKrBDbhxoxJy9/QaTOgz76ZJmfljECu4Szcq6Tbvkm7DWF5Iv4J+YbRscNNvXOL0jUo6zM/Wl+fPdUZ0GJhb3U63uNvMzy3610lpqwFo0Znw3Arudou7RQ+06k3Uz406k0WDm4y+x/1GJST8jdyohIS73OJ25eFtcXtb3N4WN63Fbf0fHMe1xd2h1KFHY0TXm2hxd5gHTxBJ0aE2oF1lcNjgdrrFXT+kxVOFzgL0WGG/we1yi7uqR4magbHr0IoeFW73qkCkHUR5t8Kmwe1Ri/vHvlHqHH6zV8PY6nb7RiV0g7jao7FpcLvV4r7QpbJxn4LmYcZWt8st7pNNQyhokY+1tDtUONmqYGxwj3uLm97gfiM3KiEb3G/kRiX0m5V4W9zeFre3xf1/0eKeIJK+2Rb3V8+G32yLm2xnO9PgdrnFfaZdie+eK1DYqcKZDpXdBrfLLe4LHSMo6RlLoEt6NLjaMxZ/X3mpxoUulUWD2+UW97XeUVzr1aC0V4OyV6NUCn2tR40rL9Q2rW63blRS3qu2iLhvvBjBzR6VRYPbrRa3rE9jk5/L+jSo7VPbtLrdanE/MjcZnil0eDaiQ92Q1qbB7XaLu1WlR7NSj/uvFHim0DI2uN/IjUrIBvcbuVEJfS7vbXF7W9zeFvfPscVNj8THrcU9bARGjCYoDEbnW9z0ki9bW3uQFoEPG11ocdPh5LIBmnoMr1PnAVdb3HR4nxFo14+9y3ZzY5gMi3uMY91nl1rcdHivcSzWI69/6CFylwF46WqLmw7vNIxlt/QLrHZziNzOcC9uhy1uOrzdADzW2MbcZOpsHYs7bHHT4a0GoFMPPNWZ8FgDPBkde92kd7PFTYc36YFWPfCcFqs2komzOy1uOtyVe3E73eJ2Nt52ucXtysPb4va2uL0t7nFocU/KzB//Fne7ObFuU+qcb3E70+R+NqKziLedbnE7anKT4+qHdfipX+Nai9u6yX2zS06lzfcHR+3ek9thi9u6tV3Wo6bs7fqrUdymjXO5xW3d5L7yUm3hn1X9WtYo3GGL27q1XdA8bGPQF7vV7rW4rZvcZzrVONelRknv6Jh6RlHIEJE41eJmanIXdKhR1KVGUbcG33V40OKmN7lduRe30y1uV+Ntb4vb2+L2trh/ri1ueiQ+Li3uYy1K/OXZML58Muhai9uZJvc3zXLGCNxhi9tek/tMpxpfPxvE/zTL3WtxWze5j9d1j+XkHSp891yBCSIpzneq3WtxW7e2T7XIqYDyx75RlPeqzdG3Gy1u6yb3jZdKiwS0dlCHqgFm93fY4rZubd/olNtErDUDWvda3NZN7pp+De4MaHF/QIOnCi0eK/R45G6L27rJ3ajUo0ExJo9b3PQmtyv34na6xe1qvO1tcXtb3B4atJ2/RZkM4EsAd83RisH8+nMAInf/UOZGGtDe4zaA5c7CfQCcJP/lkydPcOTIEaxfvx5RUVGIiYlBRkYGjh49iubmZvpGvnUGfhYA1Go19u7di6ioKAgEAsTHx0MsFiMhIQEJCQkQiUSIj4/H559/jtHRUXIDp+zBjwHA0NAQVqxYgUWLFkEoFCI1NRXLly/H6tWrsW7dOqxbtw6rV69Geno6UlJSkJWVBaVSSW5gBxN8KgAYjUZs2bIFAoEAEokEH374ITZs2IDNmzcjOzsb27dvx/bt25GdnY3Nmzdjw4YNWL58Ofbt20fCuwC8ZQ3/CwBcvXoVAoEASUlJWLVqFUpKSlBZWYmtW7dCKpUiJycHf/zjH3Hnzh1cv34dmZmZ2LhxI1auXIna2lpyA/ut4fUAsHbtWojFYqxYsQKbN2/GgwcPAAAlJSXIyMjA7t27UVNTAwCQyWRIS0vDxx9/jE2bNtHffaU1HL29vVi8eDE++OADfPTRR/jkk0+wc+dO3LlzB+RvVVFRAQCorq6GQCCAWCzGli1bsGPHDmRmZmJkZIS68bUFvLa2FmKxGKtWrcLWrVuxe/duSKVSZGZm4vbt29RnrrKyEpGRkYiJicHatWshlUqxe/duZGVlobW1lRleVlaG5ORkbNiwATt27EBOTg5yc3MhlUpRWVlJwYuLizF37lysXLkSUqkUubm5yMnJwfbt2/H48WNm+IMHD7B06VJ89NFHFgePfNe1tbXURs6fP48NGzZg165dFPyTTz5BR0cHM1yhUGDZsmXIzMyk3pFMJqPAkZGRiI6OpjZ26dIlZGRkWGxAp9Mxwm8BwN69ey3e+b179/DgwQMIBAIsXLgQH374ITZu3Ih79+6hpKQEiYmJyM7ORk5ODk6epM4apdbwPACoq6vD+vXrsX37duTk5GDr1q1YunQpRCIR1qxZA6lUCqlUijVr1lDf3q1bt2LPnj3o6uoi4dnW8P8E0AQA+fn5yMrKwq5du7Bz505kZWUhKysLO3fuRG5uLnJzc7Fz505s2bKFWn7lyhUSfIft3LIcAPR6PU6cOIEdO3Zgz5491KeGSTk5OSguLobRaCThIntnxRPkqLKyMuTm5mL37t2MGzhw4AD9Kw/zic+hWXxMjlapVLh37x6Ki4tx7NgxnDhxAhcvXsSjR4+g0WhAc6eVrjgRF8AlJ5zoIoDp7v496HcALANw3GxplebXywD8l6d/bPpf1KAfPnyI/fv3IzU1FQEBAeBwOFi6dCkOHTqEp0+fumfQKpUK2dnZFJDL5SI0NBRhYWEICwtDaGgouFwu9u7d65pBDw4OQiQSgcPhgMfjYc6cOYiMjMTChQsRExODmJgYREdHY/78+fj973+P9PR0uknYN+gVK1YgODgY4eHhiIyMhEAgwJIlS2ymFkuWLIFAIEBkZCSys7MdG/SFCxfA4XDA5/MRFRWFM2fO4PLly0hISIBEIkFKSgqWLVuG0tJSnD17FosXL0ZsbCyioqIoC2Q1aIlEgtDQUCxYsABLlixBVVUV5T6xsbFITk5GaWkpAODmzZuYN28e4uPjER8fj02bNrEb9MuXLxESEoJ58+Zh8eLFSExMxAcffIBbt25RG7h8+TIF5vF4CAsLg1AoRGJiIuLi4iCXy5mdqKqqCqGhoYiOjoZIJEJycjIkEgmEQiFu3rwJ+gktKCgIQUFBWLhwISQSCZKTkyEUCukfT0v4lStXwOfzIRAIkJiYiJSUFKSmpkIikaCkpISCFxYWYsaMGYiKioJEIkFqaipSUlKQkJCAu3fvMsNramowZ84cLF68mPpHy5Yto3ZFVVUVysvLAQAFBQWIjY1FUlISUlNTkZqaisTERDQ1NTHD5XI5IiIiEBcXR8FJh6msrASHw0FISAh1DM6cOUNN/VJTU5GWlgatVstu0JmZmRbvvLy8HDKZDDweD4GBgYiMjIRQKER1dTV++OEHhIeHQyQSITU1FQcOHLBv0DU1NVi0aBESEhKQkpICkUiEOXPmgMfjUQdPIpFg4cKF1LdXJBIhJSUFLS0tjg360KFDEAqFSE5ORlJSEoRCIYRCIfXbkAd5yZIlEAqFSEpKok8r7Bu0TqfDvn37qHdPAtn017/+1XWDLi4uxh/+8AckJyczQlevXo3r16+7b9AKhQLl5eU4duwYNQs4fvw4ZDIZVCqV16D/1Qz6/v372LNnD+Li4vD2229j6tSpiIuLQ15eHv0qwjWDViqVyMjIwNtvv40pU6bgvffeg6+vL/z8/ODn5wdfX19MnToVO3bsoE/rHBt0f38/oqKi8O6778LX1xfTp0+Hv78/AgICEBgYiMDAQMyaNQv+/v54//33IRKJoFAoHBu0wWBAcnIypkyZgmnTpsHf3x9BQUEICQnB7NmzwePxwOPxMHv2bISEhCAoKAj+/v5Yu3atY4M+e/YsBZ41axa+/vprnDt3jpqr8Pl8REREoLi4GN9++y2Cg4PB4XAwc+ZM3Lhxw75Bx8TEwM/PDzNnzkRISAj1D06fPg0Oh4Pw8HAUFxdTV9UzZsxAcHAwgoODkZ6ezm7Q3d3dmDp1KmbMmAEOh4PQ0FDMnz8f165dAwB8//33OH/+/NgJu7QUM2bMgJ+fH0JCQsDj8RASEoLh4WFmJyovL4evry9mzZqF2bNnIzw8nJq+kRsgwb6+vnj33XcREBCAsLAwhIeHg8vl4uHDh8zwixcv4v3330dQUBB4PB74fD74fD5CQ0Nx4cIFCn769Gn8+te/xsyZM6njwOfzwePxqHmODfz27duYPn06tUvIg1dYWAgAuHXrFsrKygAA33zzDUJCQhAWFmbxJhoaGpjhQ0ND8Pf3R3BwMAU/d+4cNQny8/PDtGnTqIN88uRJBAUFURuYN28efcZra9Dp6ekW7/zKlSuoqKjA7373O7zzzjvw9/cHn89HRUUFioqKMG3aNMyePRt8Ph/btm2zb9BVVVUIDAyk9jmXy8X06dPh6+uLgIAAhIaGIjQ0FAEBAdS3l8vlIjw8HE+ePHFs0Dk5OeByudQnhcvlUpN/+v4ll4eFheHIkSPOG/S2bdvA4/EQHh5OAdn0pz/9yXWDLigowIIFC1g3IBAIcPHiRfcNWi6X4/Llyzhw4ADWrFmD9evX49NPP0VZWRn9UuX/oUF7LC/cC/+ZwP93AIWmXkfePgGSAAAAAElFTkSuQmCC') no-repeat scroll 0 0; }" +
				".img-wixgallery-s {cursor: s-resize; left: 50%; margin-left: -11px; bottom: -10px; background-position: 0 -21px;}" +
				".img-wixgallery-s:hover {background-position: 0 -42px;}" +
				".img-wixgallery-e {cursor: e-resize; right: -10px; top: 50%; margin-top: -11px; background-position: 0 -84px;}" +
				".img-wixgallery-e:hover {background-position: 0 -105px;}" +
				".img-wixgallery-w {cursor: w-resize; left: -10px; top: 50%; margin-top: -11px; background-position: 0 -147px;}" +
				".img-wixgallery-w:hover {background-position: 0 -168px;}" +
				".img-wixgallery-se {cursor: se-resize; right: -10px; bottom: -10px; background-position: 0 -210px;}" +
				".img-wixgallery-se:hover {background-position: 0 -231px;}" +
				".img-wixgallery-sw {cursor: sw-resize; left: -10px; bottom: -10px; background-position: 0 -273px;}" +
				".img-wixgallery-sw:hover {background-position: 0 -294px;}" +
				".img-wixgallery-remove-left {cursor: default; left: -10px; top: -10px; background-position: 0 -336px;}" +
				".img-wixgallery-remove-right {cursor: default; right: -10px; top: -10px; background-position: 0 -336px;}" +
				".img-wixgallery-remove-left:hover,.img-wixgallery:hover .img-wixgallery-remove-right:hover {background-position: 0 -357px;}" +
				// define handles visibility
				".img-wixgallery.center:hover .center { display: block; }" +
				".img-wixgallery.left:hover .left { display: block; }" +
				".img-wixgallery.right:hover .right { display: block; }" +
				""
			);
			
			editor.$('img').not('[class*=cke_]').each(function() {
				CKEDITOR.plugins.wixgallery.placeImageByPosition( editor, this, false );
			});
			
			// ----------------------- //
			// Register event handlers //
			// ----------------------- //
			
			domHandlers.push(ev.editor.on('getData', function(event) {
				editor.$('body').html(event.data.dataValue);
				editor.$('.img-wixgallery').each(function() {
					CKEDITOR.plugins.wixgallery.unwrapImage(editor, editor.$(this));
				}).html();
				event.data.dataValue = editor.$('body').html();
			}));
			
			domHandlers.push(ev.editor.on('resize', function(event) {
				// Center all element - set left and right margin because float is still present
				editor.$('.img-wixgallery').each(function() {
					var $src = editor.$(this);
					if ( $src.hasClass('center') ) {
						var margin = ( $src.closest( CKEDITOR.plugins.wixgallery.ALLOWED_PARENTS ).outerWidth()-$src.outerWidth() ) / 2;
						$src.css( { 'marginLeft': margin, 'marginRight': margin } );
					}
				});
			}));
			
			domHandlers.push(ev.editor.document.on('mousemove', function(ev) {
				var plugin = CKEDITOR.plugins.wixgallery,
					$src = plugin.$element;
				
				if ( plugin.action == 'move' ) {
					var offsetX = ev.data.$.clientX - plugin.startX,
						offsetY = ev.data.$.clientY - plugin.startY;
					
					// Horizontal move
					if ( ev.data.$.clientX > 0) {
						var float = $src.css('float');
						
						if ( $src.hasClass('left') ) {
							var mLeft = plugin.marginLeft + offsetX,
								width = $src.outerWidth(),
								parentWidth = $src.closest( plugin.ALLOWED_PARENTS ).outerWidth(),
								mRight = parentWidth - mLeft - width,
								mDiff = mLeft - mRight;
							
							if (mDiff > 0 && mDiff < plugin.centerGap) {
								// change classes
								$src.removeClass('left').addClass('center');
								
								// set style
								// $src.css( { 'float': 'none', 'marginLeft': 'auto', 'marginRight': 'auto' } );
								
								// set initial values
								plugin.marginRight = plugin.marginLeft = (parentWidth - width) / 2;
								plugin.startX = ev.data.$.clientX;
							}
							else if (mDiff > plugin.centerGap) {
								// change classes
								$src.removeClass('left').addClass('right');
								
								// set style
								// $src.css( { 'float': 'right', 'marginLeft': Math.max(0, mRight), 'marginRight': mRight } );
								
								// set initial values
								plugin.marginRight = mRight;
								plugin.startX = ev.data.$.clientX;
							}
							else {
								$src.css( { 'float': 'left', 'marginLeft': mLeft, 'marginRight': Math.max(0, Math.min(mLeft, mRight)) } );
							}
						}
						else if ( $src.hasClass('right') ) {
							var mRight = plugin.marginRight - offsetX,
								width = $src.outerWidth(),
								parentWidth = $src.parent().outerWidth(),
								mLeft = parentWidth - mRight - width,
								mDiff = mRight - mLeft;
							
							if (mDiff > 0 && mDiff < plugin.centerGap) {
								// change classes
								$src.removeClass('right').addClass('center');
								
								// set style
								// $src.css( { 'float': 'none', 'marginLeft': 'auto', 'marginRight': 'auto' } );
								
								// set initial values
								plugin.marginRight = plugin.marginLeft = (parentWidth - width) / 2;
								plugin.startX = ev.data.$.clientX;
							}
							else if (mDiff > plugin.centerGap) {
								// $src.css( { 'float': 'left', 'marginLeft': mLeft, 'marginRight': Math.max(0, mLeft) } );
								plugin.marginLeft = mLeft;
								plugin.startX = ev.data.$.clientX;
								plugin.updateResizeHandles( $src );
							}
							else {
								$src.css( { 'float': 'right', 'marginLeft': Math.max(0, Math.min(mLeft, mRight)), 'marginRight': mRight } );
							}
						}
						else {
							// $src.hasClass('center')
							var mDiff = plugin.startX - ev.data.$.clientX;
							if ( mDiff > plugin.centerGap ) {
								// float left
								$src.removeClass('center').addClass('left');
								
								// set initial values
								plugin.marginLeft -= mDiff;
								plugin.marginRight += mDiff;
								plugin.startX = ev.data.$.clientX;
							}
							else if ( mDiff < -plugin.centerGap ) {
								// float left
								$src.removeClass('center').addClass('right');
								
								// set initial values (mDiff is negative)
								plugin.marginLeft = plugin.marginLeft - mDiff;   // Mean plugin.marginLeft - Math.abs(mDiff)
								plugin.marginRight = plugin.marginRight + mDiff; // Mean plugin.marginRight + Math.abs(mDiff)
								plugin.startX = ev.data.$.clientX;
							}
							else {
								$src.css( { 'float': 'left', 'marginLeft': plugin.marginLeft, 'marginRight': plugin.marginRight } );
							}
						}
					}
					
					// vertical move
					if ( ev.data.$.clientY > 0 ) {
						var $parent = $src.closest( plugin.ALLOWED_PARENTS ),
							mTop = plugin.marginTop + offsetY;
						
						// Add all non allowed parrents (like <ul>, <li>, etc.) to current parent
						$parent = CKEDITOR.plugins.wixgallery.getWithNotAllowed( $parent );
						
						// Move down below parent
						if ( mTop > $parent.outerHeight() && $parent.isNotLastOrEmpty ) {
							var $next = CKEDITOR.plugins.wixgallery.getNext( $parent ),
								off = $parent.position().top;
							
							// move image from current parent to next parent sibling
							$src.prependTo( $next.first() );
							
							// reset starting point -> mext drag event appear automatically 
							plugin.marginTop = mTop = mTop - ($next.position().top - off);
							plugin.startY = ev.data.$.clientY;
							
							$src.css( 'marginTop', mTop ).css( 'marginBottom', Math.max( 0, Math.min($parent.outerHeight()-$src.outerHeight()-mTop-(parseFloat($parent.css('lineHeight'))/2), mTop) ) );
						}
						else if ( mTop <= 0 ) {
							var $prev = CKEDITOR.plugins.wixgallery.getPrevious( $parent ),
								moved = false;
							
							if ($prev.length > 0) {
								var gapSize = $parent.position().top - ( $prev.position().top + $prev.outerHeight() );
								// Move up above parent
								if ( -mTop > gapSize ) {
									plugin.marginTop = mTop = $prev.outerHeight();
									plugin.startY = ev.data.$.clientY;
									
									$src.prependTo( $prev.first() );
									moved = true;
									
									$src.css( 'marginTop', mTop ).css( 'marginBottom', Math.max( 0, Math.min($parent.outerHeight()-$src.outerHeight()-mTop-(parseFloat($parent.css('lineHeight'))/2), mTop) ) );
								}
							}
							
							// Move inside paret to negative values
							if (!moved) {
								$src.css( { 'marginTop': mTop, 'marginBottom': Math.max( 0, mTop ) } );
							}
						}
						// Move up & down inside parent
						else {
							$src.css( 'marginTop', mTop ).css( 'marginBottom', Math.max( 0, Math.min($parent.outerHeight()-$src.outerHeight()-mTop-(parseFloat($parent.css('lineHeight'))/2), mTop) ) );
						}
					}
					
					editor.getSelection().removeAllRanges();
				}
				else if ( plugin.action == 'resize-height' ) {
					var offsetY = ev.data.$.clientY - plugin.startY;
				
					// Vertical resize
					if ( ev.data.$.clientY > 0 ) {
						$src.css( { 'height': plugin.height + offsetY } );
					}
				}
				else if ( plugin.action == 'resize-width' || plugin.action == 'resize-width-reversed' ) {
					var offsetX = ev.data.$.clientX - plugin.startX,
						addX = $src.hasClass('center') ? 2*offsetX : offsetX,
						newWidth = plugin.width + ( plugin.action == 'resize-width' ? addX : -addX );
				
					// Horizontal resize
					if ( ev.data.$.clientX > 0) {
						$src.css( { 'width': newWidth } );
					}
					
					// Center - set left and right margin because float is still present
					if ( $src.hasClass('center') ) {
						var margin = ( $src.closest( plugin.ALLOWED_PARENTS ).outerWidth()-newWidth ) / 2;
						$src.css( { 'marginLeft': margin, 'marginRight': margin } );
					}
				}
				else if ( plugin.action == 'resize-both' || plugin.action == 'resize-both-reversed' ) {
					var offsetX = ev.data.$.clientX - plugin.startX,
						offsetY = ev.data.$.clientY - plugin.startY,
						addX = $src.hasClass('center') ? 2*offsetX : offsetX,
						newWidth = plugin.width + ( plugin.action == 'resize-both' ? addX : -addX ),
						newHeight = plugin.height + offsetY;
						
					
					if ( newWidth/plugin.width > newHeight/plugin.height ) {
						// Horizontal resize
						if ( ev.data.$.clientX > 0 ) {
							newHeight = plugin.height * (newWidth/plugin.width);
						}
					}
					else {
						// Vertical resize
						if ( ev.data.$.clientY > 0 ) {
							newWidth = plugin.width * (newHeight/plugin.height);
							addX = addX * (newHeight/plugin.height);
						}
					}
					
					// set new Width and Height
					$src.css( { 'width': newWidth, 'height': newHeight } );
					
					// Center - set left and right margin because float is still present
					if ( $src.hasClass('center') ) {
						var margin = ( $src.closest( plugin.ALLOWED_PARENTS ).outerWidth()-newWidth ) / 2;
						$src.css( { 'marginLeft': margin, 'marginRight': margin } );
					}
				}
			}));
			
			domHandlers.push(ev.editor.document.on('mousedown', function(ev) {
				var plugin = CKEDITOR.plugins.wixgallery,
					$src = editor.$(ev.data.$.srcElement || ev.data.$.target);
				
				if ( $src.is( '.img-wixgallery-cover' ) ) {
					plugin.action = 'move';
				}
				else if ( $src.is( '.img-wixgallery-s' ) ) {
					plugin.action = 'resize-height';
				}
				else if ( $src.is( '.img-wixgallery-w' ) ) {
					plugin.action = 'resize-width-reversed';
				}
				else if ( $src.is( '.img-wixgallery-e' ) ) {
					plugin.action = 'resize-width';
				}
				else if ( $src.is( '.img-wixgallery-sw' ) ) {
					plugin.action = 'resize-both-reversed';
				}
				else if ( $src.is( '.img-wixgallery-se' ) ) {
					plugin.action = 'resize-both';
				}
				else if ( $src.is( '.img-wixgallery-remove-left' ) || $src.is( '.img-wixgallery-remove-right' ) ) {
					plugin.action = 'remove';
				}
				else {
					plugin.action = null;
				}
				
				if ( plugin.action ) {
					$src = $src.closest('.img-wixgallery');
					
					var marginLeft = $src.css('marginLeft').match(/([+-]?\d+(?:\.\d+)?(?:e\d+)?)px/i),
						marginRight = $src.css('marginRight').match(/([+-]?\d+(?:\.\d+)?(?:e\d+)?)px/i),
						marginTop = $src.css('marginTop').match(/([+-]?\d+(?:\.\d+)?(?:e\d+)?)px/i);
				
					ev.data.stopPropagation();
					
					// get starting point
					plugin.startX = ev.data.$.clientX;
					plugin.startY = ev.data.$.clientY;
					plugin.$element = $src;
					plugin.marginLeft = ( marginLeft && parseInt(marginLeft[1]) ) || 0 ;
					plugin.marginRight = ( marginRight && parseInt(marginRight[1]) ) || 0 ;
					plugin.marginTop = ( marginTop && parseInt(marginTop[1]) ) || 0 ;
					plugin.width = $src.outerWidth();
					plugin.height = $src.outerHeight();
					
					editor.setReadOnly(true);
				}
			}));
			
			domHandlers.push(ev.editor.document.on('mouseup', function(ev) {
				if ( CKEDITOR.plugins.wixgallery.action ) {
					var $src = editor.$(ev.data.$.srcElement || ev.data.$.target);
					if ( CKEDITOR.plugins.wixgallery.action == 'remove' && ( $src.is('.img-wixgallery-remove-left') || $src.is('.img-wixgallery-remove-right') ) ) {
						$src.closest('.img-wixgallery').remove();
					}
					CKEDITOR.plugins.wixgallery.action = null;
					editor.setReadOnly(false);
				}
			}));
			
			// remove dom handlers
			domHandlers.push(ev.editor.on('contentDomUnload', function(event) {
				for (var i in domHandlers) {
					domHandlers[i].removeListener();
				}
				domHandlers = [];
			}));
			
		});
	}
});

CKEDITOR.plugins.wixgallery = {
	width: 0,
	height: 0,
	marginLeft: 0,
	marginRight: 0,
	marginTop: 0,
	startX: 0,
	startY: 0,
	action: null,
	$element: null,
	ignoreNextDrag: false,
	// Constatnt
	centerGap: 20,
	ALLOWED_PARENTS: 'p,blockquote',
	
	updateResizeHandles: function( $src ) {
		/*
		if ( $src.css('float') === 'right' ) {
			$src.find('.img-wixgallery-e, .img-wixgallery-se, .img-wixgallery-remove-left').hide();
			$src.find('.img-wixgallery-w, .img-wixgallery-sw, .img-wixgallery-remove-right').show();
		}
		else {
			$src.find('.img-wixgallery-e, .img-wixgallery-se, .img-wixgallery-remove-left').show();
			$src.find('.img-wixgallery-w, .img-wixgallery-sw, .img-wixgallery-remove-right').hide();
		}
		*/
	},
	
	placeImageByPosition: function(editor, imageElement, adaptSize, width, height) {
		var $img = editor.$( imageElement ),
			$parent = $img.closest( CKEDITOR.plugins.wixgallery.ALLOWED_PARENTS );
		
		if (typeof ( width ) == 'undefined' ) {
			width = $img.innerWidth();
		}
		if (typeof ( height ) == 'undefined' ) {
			height = $img.innerHeight();
		}
		
		var isTextBefore = CKEDITOR.plugins.wixgallery.isTextBefore( $img ),
			isTextAfter = CKEDITOR.plugins.wixgallery.isTextAfter( $img );
		
		if ( !isTextBefore && !isTextAfter ) {
			var imgWidth = adaptSize ? Math.min( width, $parent.innerWidth() ) : width,
				imgHeight = adaptSize ? (imgWidth / width) * height : height,
				margin = adaptSize ? '0 auto' : $img.css('margin');
			// centered image
			CKEDITOR.plugins.wixgallery.wrapImage( editor, $img.css( { 'float': 'none', 'position': '', 'margin': margin, 'display': 'block', 'width': imgWidth, 'height': imgHeight } ) );
		}
		else if ( ( isTextBefore && !isTextAfter ) || ( !isTextBefore && isTextAfter ) ) {
			var	imgHeight = adaptSize ? Math.min( height, $parent.innerHeight() ) : height,
				imgWidth = adaptSize ? (imgHeight / height) * width : width,
				margin = adaptSize ? '0 1em' : $img.css('margin');
			
			if (isTextBefore) {
				// set image to the begining of parent paragraph
				$img.prependTo($parent);
			}
			// image on left or right
			CKEDITOR.plugins.wixgallery.wrapImage( editor, $img.css( { 'float': adaptSize ? ( isTextBefore ? 'right' : 'left' ) : $img.css('float'), 'position': '', 'margin': margin, 'height': imgHeight, 'width': imgWidth } ) );
		}
		else {
			var imgHeight = adaptSize ? Math.min( height, parseFloat( $parent.css('lineHeight') ), parseFloat( $parent.css('fontSize') ) ) : height,
				imgWidth = adaptSize ? (imgHeight / height) * width : width,
				margin = adaptSize ? '0' : $img.css('margin');
			// inline image
			$img.css( { 'float': 'none', 'position': '', 'margin': margin, 'height': imgHeight, 'width': imgWidth } ).addClass('img-nodrag');
		}
	},

	wrapImage: function( editor, $img ) {
		var $wrapper = editor.$('<span class="img-wixgallery" contenteditable="false"></span>'),
			$parent = $img.closest( CKEDITOR.plugins.wixgallery.ALLOWED_PARENTS );
			float = $img.css('float');
		
		// wrap image
		$wrapper.css({
			'float':  float == 'none' ? 'left' : float,
			'width':  $img.css('width'),
			'height': $img.css('height'),
			'margin': $img.css('margin')
		}).removeClass('left center right').addClass( $img.hasClass('center') || float == 'none' ? 'center' : float );
		$img.css({
			'float': 'none',
			'width': '100%',
			'height': '100%',
			'margin': 0
		}).attr('contentEditable', 'false');
		
		// prepend wrapper to allowed parents only
		if ( $img.parent()[0] != $parent[0] ) {
			$parent.prepend($wrapper);
			$wrapper.prepend($img);
		}
		else {
			$img.wrap($wrapper);
			$wrapper = $img.parent();
		}
		
		// add handlers
		$wrapper.append('<span class="img-wixgallery-cover" contentEditable="false"></span>' +
			'<span class="img-wixgallery-handle img-wixgallery-remove-left left center" contentEditable="false"></span>' +
			'<span class="img-wixgallery-handle img-wixgallery-remove-right right" contentEditable="false"></span>' +
			'<span class="img-wixgallery-handle img-wixgallery-s left right center" contentEditable="false"></span>' +
			'<span class="img-wixgallery-handle img-wixgallery-e left center" contentEditable="false"></span>' +
			'<span class="img-wixgallery-handle img-wixgallery-w right center" contentEditable="false"></span>' +
			'<span class="img-wixgallery-handle img-wixgallery-sw right center" contentEditable="false"></span>' + 
			'<span class="img-wixgallery-handle img-wixgallery-se left center" contentEditable="false"></span>' +
			'<span class="img-wixgallery-axis center" contentEditable="false"></span>'
		);
		
		CKEDITOR.plugins.wixgallery.updateResizeHandles( $wrapper );
		
		return $img;
	},

	unwrapImage: function( editor, $wrapper ) {
		var $img = $wrapper.find('img'),
			width = $wrapper.outerWidth(),
			height = $wrapper.outerHeight(),
			mTop = $wrapper.css('marginTop'),
			mLeft = $wrapper.css('marginLeft'),
			mBottom = $wrapper.css('marginBottom'),
			mRight = $wrapper.css('marginRight'),
			imgClass = $wrapper.css('float');
		
		if ( $wrapper.hasClass('center') ) {
			var mLeftPx = parseFloat( mLeft );
			
			width = parseFloat( width ) / $wrapper.closest( CKEDITOR.plugins.wixgallery.ALLOWED_PARENTS ).outerWidth() * 100; // Without % sign
			height = 'auto';
			mLeft = mRight = ( 100 - width ) / 2; // Without % sign
			mTop = parseFloat( mTop ) * mLeft / mLeftPx + '%';
			mBottom = parseFloat( mBottom ) * mLeft / mLeftPx + '%';
			// Add '%' sign at the end
			width += '%';
			mLeft += '%';
			mRight += '%';
			
			imgClass = 'center';
		}
		
		// unwrap img
		$img.insertAfter( $wrapper ).css({
			'float':  $wrapper.css('float'),
			'width':  width,
			'height': height,
			'marginTop': mTop,
			'marginLeft': mLeft,
			'marginBottom': mBottom,
			'marginRight': mRight,
		}).removeClass( 'left center right' ).addClass( imgClass ).removeAttr('contentEditable');
		$wrapper.remove();
		
		// FIX: Remove empty paragraphs from begin and end.
		editor.$('p:empty').remove();
	},
	
	isTextBefore: function( $elem ) {
		var htmlElem = $elem.get(0);
		while (htmlElem.previousSibling) {
			if ( $.trim( htmlElem.previousSibling.nodeValue ) != '' ) {
				return true;
			}
			htmlElem = htmlElem.previousSibling;
		}
		
		return $elem.prev().length > 0 && !$elem.prev().is('br,img,.img-wixgallery');;
	},
	
	isTextAfter: function( $elem ) {
		var htmlElem = $elem.get(0);
		while (htmlElem.nextSibling) {
			if ( $.trim( htmlElem.nextSibling.nodeValue ) != '' ) {
				return true;
			}
			htmlElem = htmlElem.nextSibling;
		}
		
		return $elem.next().length > 0 && !$elem.next().is('br,img,.img-wixgallery');
	},
	
	getWithNotAllowed: function( $current ) {
		
		var $withNotAllowed = $current.add( $current.nextUntil( CKEDITOR.plugins.wixgallery.ALLOWED_PARENTS ) );
		$withNotAllowed.isNotLastOrEmpty = false;
		
		$withNotAllowed.each(function() {
			var $this = $(this);
			$withNotAllowed.isNotLastOrEmpty = $withNotAllowed.isNotLastOrEmpty || !$this.is(':last-child') || $this.text();
		});
		$withNotAllowed.outerHeight = function() {
			var $last = this.last();
			// if last is empty, $withNotAllowed is empty too
			if ($last.length == 0) {
				return 0;
			}
			
			// real height of all elements (assumtion is elements are siblings)
			return ($last.last().position().top - this.first().position().top) + $last.last().outerHeight();
		};
		
		return $withNotAllowed;
	},
	
	getNext: function( $current ) {
		var $next = $current.last().nextAll( CKEDITOR.plugins.wixgallery.ALLOWED_PARENTS ).first();
		
		if ( $next.length == 0 ) {
			$next = $( '<p></p>' ).insertAfter( $current );
		}
		return CKEDITOR.plugins.wixgallery.getWithNotAllowed( $next );
	},
	
	getPrevious: function( $current ) {
		return CKEDITOR.plugins.wixgallery.getWithNotAllowed( $current.first().prevAll( CKEDITOR.plugins.wixgallery.ALLOWED_PARENTS ).first() );
	}
};

CKEDITOR.wixgalleryCommand = function() {};
CKEDITOR.wixgalleryCommand.prototype = {
	exec: function( editor ) {
		Wix.Settings.openMediaDialog(Wix.Settings.MediaType.IMAGE, false, function(data) {
			var imageUrl = Wix.Utils.Media.getImageUrl(data.relativeUri);
			
			// create IMG element.
			var imageElement = editor.document.createElement( 'img' );
			imageElement.setAttribute( 'alt', '' );
			imageElement.setAttribute( 'src', imageUrl );
			imageElement.setAttribute( 'style', 'position: absolute;' );
			
			try {
				editor.insertElement( imageElement );
			} catch(err) {
				console.log('insertElement errror, ' + err);
			}

			// Wrap IMG element
			CKEDITOR.plugins.wixgallery.placeImageByPosition( editor, imageElement.$, true, data.width, data.height );
				
			
		});
	}
};
